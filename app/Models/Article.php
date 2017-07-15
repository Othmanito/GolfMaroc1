<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'id_article';

    protected $fillable = [
        'id_article', 'id_fournisseur', 'id_categorie', 'id_marque',
        'designation',
        'code', 'ref', 'alias',
        'couleur', 'sexe', 'prix_a', 'prix_v',
        'deleted', 'image', 'valide'
    ];

    public static function getPrixPromo($p_id_article, $p_id_magasin)
    {
        $prixHT = Article::where('id_article', $p_id_article)->first()->prix_vente;
        $prixTTC = $prixHT * 1.2;

        if (Promotion::hasPromotion($p_id_article, $p_id_magasin)) {
            $taux = Promotion::getTauxPromo($p_id_article, $p_id_magasin);
            $prix = $prixTTC * (1 - $taux / 100);
            return $prix;
        } else {
            return $prixTTC;
        }
    }

    static function getNextID()
    {
        $lastRecord = DB::table('articles')->orderBy('id_article', 'desc')->first();
        $result = ($lastRecord == null ? 1 : $lastRecord->id_article + 1);
        return $result;
    }

    public static function CodeExists($code)
    {
        $data = self::where('code', $code)->get()->first();
        if ($data == null)
            return false;
        else return true;
    }

    public static function hasNonValideArticles()
    {
        $data = self::where('valide', false)->where('deleted', false)->get();
        if ($data->isEmpty())
            return false;
        else return true;
    }

    public static function nonValideArticles()
    {
        $data = self::where('valide', false)->where('deleted', false)->get();
        return $data;

    }

    public static function CodeExistForUpdate($p_id, $code)
    {
        $x = self::where('code', $code)->where('id_article', '!=', $p_id)->first();
        if ($x == null)
            return false;
        else
            return true;
    }


    public static function getMarque($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return Marque::getLibelle($data->id_marque);
        else return null;
    }

    public static function getCategorie($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return Categorie::getLibelle($data->id_categorie);
        else return null;
    }




    public static function getFournisseur($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return Fournisseur::getLibelle($data->id_fournisseur);
        else return null;
    }

    public static function getDesignation($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->designation;
        else return null;
    }

    public static function getCode($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->code;
        else return null;
    }

    public static function getRef($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->ref;
        else return null;
    }

    public static function getAlias($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->alias;
        else return null;
    }

    public static function getSexe($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->sexe;
        else return null;
    }

    public static function getCouleur($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->couleur;
        else return null;
    }

    public static function getPrixTTC($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return number_format(($data->prix_v * 1.2), 2);
        else return null;
    }

    public static function getPrixHT($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();

        if ($data != null)
            return number_format($data->prix_v,2);
        else return null;
    }

  

    public static function getPrixGrosTTC($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return number_format(($data->prix_v * 1.2), 2);
        else return null;
    }

    public static function getPrixGrosHT($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return number_format($data->prix_v,2);
        else return null;
    }

    public static function getImage($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        if ($data != null)
            return $data->image;
        else return null;
    }


}