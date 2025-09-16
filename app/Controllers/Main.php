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
 
    public function index()
    {
        $seasonModel = new Season();
 
        $seasons = $seasonModel
            ->orderBy('start', 'ASC')
            ->findAll();
 
        $grouping = new Grouping();
        $poDekadach = $grouping->byDecade($seasons);
 
        $data['poDekadach'] = $poDekadach;
       
        echo view('index', $data);
    }
 
    /**
     * Detail sezóny – soutěže (ligy) v dané sezóně
     */
    public function sezona($id)
    {
        $leagueSeasonModel = new LeagueSeason();
        $leagueModel       = new League();
 
        // najdeme všechny propojené ligy
        $leagueSeasons = $leagueSeasonModel
            ->asObject()
            ->where('id', $id)
            ->findAll();
 
        $souteze = [];
        foreach ($leagueSeasons as $ls) {
            $liga = $leagueModel->find($ls->id);
            if ($liga) {
                $souteze[] = $liga;
            }
        }
 
        return view('sezony/sezona', [
            'souteze' => $souteze
        ]);
    }

    public function novinky()
    {
        // načteme články
        $articles = $this->article
            ->orderBy('date', 'DESC')
            ->where('top', 1)
            ->findAll(5);
   
        // poskládáme HTML pro každý článek
       
   
        // pošleme do view
        return view("novinky", ['articles' => $articles]);
    }

    public function article($id){
 
        $clanek = $this->article->find($id);
 
        $data["article"] = $clanek;
 
        echo view("clanek", $data);
    }
    public function administrace() {
        echo view('administrace');
    }
 
}
 