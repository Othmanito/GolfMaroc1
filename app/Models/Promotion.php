<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'id_promotion';

    protected $fillable = [
        'id_promotion', 'id_article', 'id_magasin',
        'taux', 'date_debut', 'date_fin', 'active',
        'deleted',
    ];
    public static function getMagasin($p_id)
    {
        $data = self::where('id_magasin', $p_id)->get()->first();
        if ($data != null)
            return Magasin::getLibelle($data->id_magasin);
        else return null;
    }


    //fonction static permet de verifier si un promotion d un article dans un magasin est disponible
    public static function hasPromotion($p_id_article)
    {
        $p_id_magasin = Session::get('id_magasin');
        $promo = collect(Promotion::where('id_article', $p_id_article)->where('id_magasin', $p_id_magasin)->where('active', true)->get());
        $now = new Carbon();

        if (!$promo->isEmpty()) {
            $d = Carbon::parse($promo->first()->date_debut);
            $f = Carbon::parse($promo->first()->date_fin);
            if ($now->greaterThanOrEqualTo($d) && $now->lessThanOrEqualTo($f)) {
                return true;
            } else return false;
        } else {
            return false;
        }
    }
    public static function getPrixPromotion($p_id)
    {
        $data = self::where('id_article', $p_id)->get()->first();
        $promo=self::getTauxPromo($data->id_article);
        if ($data != null)
            return number_format(Article::getPrixTTC($data->id_article)-((Article::getPrixTTC($data->id_article)*$promo)/100),2);
        else return null;
    }

    public static function getTauxPromo($p_id_article)
    {
        $p_id_magasin = Session::get('id_magasin');
        //return $promo = collect(Promotion::where('id_article', $p_id_article)->where('id_magasin', $p_id_magasin)->where('active', true)->get())->first()->taux;
        if (Promotion::hasPromotion($p_id_article)) {
            return $promo = collect(Promotion::where('id_article', $p_id_article)->where('id_magasin', $p_id_magasin)->where('active', true)->get())->first()->taux;
        } else return 0;

    }
    public static function getDateDebut($p_id)
    {
        $data = self::where('id_promotion', $p_id)->get()->first();
        if ($data != null)
            return $data->date_debut;
        else return null;
    }
    public static function getDateFin($p_id)
    {
        $data = self::where('id_promotion', $p_id)->get()->first();
        if ($data != null)
            return $data->date_fin;
        else return null;
    }
    public static function getTaux($p_id)
    {
        $data = self::where('id_promotion', $p_id)->get()->first();
        if ($data != null)
            return $data->taux;
        else return null;
    }

}
