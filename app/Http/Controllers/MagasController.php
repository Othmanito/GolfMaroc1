<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Promotion;
use App\Models\Stock;
use App\Models\Article;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Magasin;
use App\Models\Marque;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

class MagasController extends Controller
{
    public function home()
    {
        return view('Espace_Magas.dashboard')->withAlertInfo("Bienvenue dans votre espace magasinier")->withAlignInfo("center")->withTimerInfo(2000);
    }
    public function profile()
    {
        $data = User::where('id', Session::get('id_user'))->get()->first();
        //dump($data);
        return view('Espace_Magas.profile')->with('data', $data);
    }

    /********************************************************
     * Afficher les listes
     *********************************************************/
    public function marques()
    {
        $data = Marque::where('deleted', false)->get();
        return view('Espace_Magas.liste-marques')->withData($data);
    }

    public function categories()
    {
        $data = Categorie::where('deleted', false)->get();
        return view('Espace_Magas.liste-categories')->withData($data);
    }

    public function fournisseurs()
    {
        $data = Fournisseur::where('deleted', false)->get();
        return view('Espace_Magas.liste-fournisseurs')->withData($data);
    }

    public function agents()
    {
        $data = Agent::where('deleted', false)->get();
        return view('Espace_Magas.liste-agents')->withData($data);
    }

    public function articles()
    {
        $data = Article::where('deleted', false)->get();
        $marques = Marque::all();
        $fournisseurs = Fournisseur::all();
        $categories = Categorie::all();

        return view('Espace_Magas.liste-articles')->withData($data);
    }

    public function magasins()
    {
        $data = Magasin::where('deleted', false)->where('id_magasin', '!=', 1)->get();
        return view('Espace_Magas.liste-magasins')->withData($data);
    }
    public function clients()
    {
        $data = Client::where('deleted', false)->where('id_magasin', Session::get('id_magasin'))->get();
        return view('Espace_Magas.liste-clients')->withData($data);
    }

    public function promotions()
    {
        $data = Promotion::where('deleted', false)->where('id_magasin', Session::get('id_magasin'))->get();
        return view('Espace_Magas.liste-promotions')->withData($data);
    }
    /******************************************************************************************************************/

    /********************************************************
     * Afficher les infos
     *********************************************************/
    public function client($p_id)
    {
        $data = Client::find($p_id);
        if ($data == null)
            return redirect()->back()->with('alert_warning', "Le client choisi n'existe pas.");

        return view('Espace_Magas.info-client')->withData($data);
    }

    public function marque($p_id)
    {
        $data = Marque::find($p_id);
        if ($data == null)
            return redirect()->back()->with('alert_warning', "La marque choisie n'existe pas.");

        $articles = Article::where('id_marque', $p_id)->where('deleted', false)->where('valide', true)->get();
        return view('Espace_Magas.info-marque')->withData($data)->withArticles($articles);
    }

    public function categorie($p_id)
    {
        $data = Categorie::find($p_id);
        if ($data == null)
            return redirect()->back()->with('alert_warning', "La categorie choisie n'existe pas.");

        $articles = Article::where('id_categorie', $p_id)->where('deleted', false)->where('valide', true)->get();
        return view('Espace_Magas.info-categorie')->withData($data)->withArticles($articles);
    }

    public function fournisseur($p_id)
    {
        $data = Fournisseur::find($p_id);
        if ($data == null)
            return redirect()->back()->with('alert_warning', "La categorie choisie n'existe pas.");

        $articles = Article::where('id_fournisseur', $p_id)->where('deleted', false)->where('valide', true)->get();
        $agents = Agent::where('id_fournisseur', $p_id)->get();
        return view('Espace_Magas.info-fournisseur')->withData($data)->withArticles($articles)->withAgents($agents);
    }

    public function agent($p_id)
    {
        $data = Agent::find($p_id);
        if ($data == null)
            return redirect()->back()->with('alert_warning', "L'agent choisi n'existe pas.");

        return view('Espace_Magas.info-agent')->withData($data);//->withArticles($articles);
    }

    public function article($p_id)
    {
        $data = Article::find($p_id);
        $marques = Marque::all();
        $fournisseurs = Fournisseur::all();
        $categories = Categorie::all();

        if ($data == null)
            return redirect()->back()->with('alert_warning', "L'article choisi n'existe pas.");

        return view('Espace_Magas.info-article')->withData($data)->withMarques($marques)->withFournisseurs($fournisseurs)->withCategories($categories);
    }

    public function magasin($p_id)
    {
        if ($p_id == 1)
            return redirect()->back()->withInput()->with('alert_info',"Vous ne pouvez pas acceder a ce magasin de cette maniere.");

        $data = Magasin::find($p_id);
        //$stock = Stock::where('id_magasin', $p_id)->get();

        if ($data == null)
            return redirect()->back()->with('alert_warning', "Le magasin choisi n'existe pas.");

        return view('Espace_Magas.info-magasin')->withData($data);//->withStock($stock);
    }

    public function main_magasin()
    {
        $data = Magasin::find(1);
        //$stock = Stock::where('id_magasin', 1)->get();

        return view('Espace_Magas.info-main_magasin')->withData($data);//->withStock($stock);
    }


}
