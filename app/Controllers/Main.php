<?php
 
namespace App\Controllers;
 
use App\Models\Season;
use App\Models\League;
use App\Models\LeagueSeason;
use App\Models\Article;
use App\Libraries\Grouping;
 
class Main extends BaseController
{
 
    protected $season;
    protected $leagueSeason;
    protected $league;
    protected $article;
 
    public function __construct()
    {
        $this->season = new Season();
        $this->leagueSeason = new LeagueSeason();
        $this->league = new League();
        $this->article = new Article();
    }
 
    private function slugify($text)
    {
        $text = strtolower($text);
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
 
    public function index()
    {
        $seasons = $this->season
            ->orderBy('start', 'ASC')
            ->findAll();
 
        $grouping = new Grouping();
        $poDekadach = $grouping->byDecade($seasons);
 
        $data['poDekadach'] = $poDekadach;
       
        echo view('index', $data);
    }
 
    public function sezona($id)
    {
        $souteze = $this->leagueSeason
            ->select('league.*')
            ->join('league', 'league.id = league_season.id_league')
            ->where('league_season.id_season', $id)
            ->asObject()
            ->findAll();
 
        $sezona = $this->season->find($id);
 
        return view('sezona', [
            'sezona' => $sezona,
            'souteze' => $souteze
        ]);
    }
 
    public function novinky()
    {
        $articles = $this->article
            ->orderBy('date', 'DESC')
            ->where('top', 1)
            ->findAll(5);
   
        return view("novinky", ['articles' => $articles]);
    }
 
    public function article($id){
        $clanek = $this->article->find($id);
        $data["article"] = $clanek;
        echo view("clanek", $data);
    }
 
    public function administrace(){
        $clanek = $this->article->findAll();
        $data["article"] = $clanek;
        echo view("administrace", $data);
    }
 
    public function create(){
        echo view("create");
    }
 
    public function store()
    {
        $validace = $this->validate([
            'title'  => 'required|max_length[255]',
            'date'   => 'required|valid_date',
            'text'   => 'required',
            'photo'  => 'max_size[photo,2048]|is_image[photo]'
        ]);
 
        if (!$validace) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
 
        $photo = $this->request->getFile('photo');
        $noveJmeno = null;
 
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $noveJmeno = $photo->getRandomName();
            $photo->move(FCPATH . 'obrazky/sigma/', $noveJmeno); // ukládá se fyzicky do obrazky/sigma
        }
 
        // vložíme článek
        $id = $this->article->insert([
            'title' => $this->request->getPost('title'),
            'photo' => $noveJmeno ?: null, // v DB jen název souboru
            'date'  => strtotime($this->request->getPost('date')),
            'top'   => $this->request->getPost('top') ? 1 : 0,
            'text'  => $this->request->getPost('text'),
        ], true);
 
        $slug = $this->slugify($this->request->getPost('title'));
 
        $this->article->update($id, [
            'link' => 'article/' . $id . '-' . $slug
        ]);
 
        return redirect()->to('/administrace')->with('success', 'Článek byl úspěšně přidán.');
    }
 
    public function delete($id)
    {
        $clanek = $this->article->find($id);
 
        if (!$clanek) {
            return redirect()->back()->with('error', 'Článek nebyl nalezen.');
        }
 
        // smažeme fotku ze složky obrazky/sigma/
        if ($clanek->photo && file_exists(FCPATH . 'obrazky/sigma/' . $clanek->photo)) {
            unlink(FCPATH . 'obrazky/sigma/' . $clanek->photo);
        }
 
        $this->article->delete($id);
 
        return redirect()->to('/administrace')->with('success', 'Článek byl úspěšně smazán.');
    }
 
    public function edit($id)
    {
        $article = $this->article->find($id);
 
        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Článek s ID $id nebyl nalezen.");
        }
 
        return view('edit', ['article' => $article]);
    }
 
    public function update($id)
    {
        $data = [
            'title'     => $this->request->getPost('title'),
            'date'  => strtotime($this->request->getPost('date')),
            'link'      => 'article/' . $this->request->getPost('link'),
            'text'      => $this->request->getPost('text'),
            'top'       => $this->request->getPost('top') ? 1 : 0,
            'published' => $this->request->getPost('published') ? 1 : 0,
        ];
 
        // zpracování uploadu fotky
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'obrazky/sigma/', $newName);
            $data['photo'] = $newName; // jen název do DB
        }
 
        $this->article->update($id, $data);
 
        return redirect()->to('/administrace')->with('success', 'Článek byl úspěšně upraven.');
    }
 
}
