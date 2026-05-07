<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Continents;
use App\Models\Regions;
use App\Models\Countries;
use App\Models\Provinces;
use App\Models\Cities;
use Auth;
use DB;

class RegionalSettingsController extends Controller
{
    
    public function index() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	return view('dashboard.regional_settings.index', $dataBag);
    }

    public function continentsList() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	$dataBag['allContinents'] = Continents::where('status', '!=', '3')->get();
    	return view('dashboard.regional_settings.continents_list', $dataBag);	
    }

    public function continentsAdd() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	return view('dashboard.regional_settings.continents_add', $dataBag);
    }

    public function continentsSave(Request $request) {

    	$Continents = new Continents;
    	$Continents->continents_name = trim(ucfirst($request->input('continents_name')));
    	$Continents->status = trim($request->input('status'));
        $Continents->lat = trim($request->input('lat'));
        $Continents->lng = trim($request->input('lng'));
    	$Continents->created_by = Auth::user()->id;
    	$res = $Continents->save();
    	if( $res ) {
    		return back()->with('msg', 'Continents Added Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function continentsEdit($id) {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	$dataBag['continent'] = Continents::findOrFail($id);
    	return view('dashboard.regional_settings.continents_add', $dataBag);
    }

    public function continentsUpdate(Request $request, $id) {
    	$Continents = Continents::find($id);
    	$Continents->continents_name = trim(ucfirst($request->input('continents_name')));
    	$Continents->status = trim($request->input('status'));
    	$Continents->updated_by = Auth::user()->id;
    	$Continents->updated_at = date('Y-m-d H:i:s');
        $Continents->lat = trim($request->input('lat'));
        $Continents->lng = trim($request->input('lng'));
    	$res = $Continents->save();
    	if( $res ) {
    		return back()->with('msg', 'Continents Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function continentsDelete($id) {
    	$del = Continents::findOrFail($id);
    	$res = $del->delete();
    	if( $res ) {
    		return back()->with('msg', 'Continents Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    /*** Region ***/

    public function regionList() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	$dataBag['allRegions'] = Regions::where('status', '!=', '3')->get();
    	return view('dashboard.regional_settings.region_list', $dataBag);
    }

    public function regionAdd() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	$dataBag['continents'] = Continents::where('status', '=', '1')->orderBy('continents_name', 'asc')->get();
    	return view('dashboard.regional_settings.region_add', $dataBag);
    }

    public function regionSave(Request $request) {

    	$Regions = new Regions;
    	$Regions->continent_id = trim($request->input('continent_id'));
    	$Regions->region_name = trim(ucfirst($request->input('region_name')));
    	$Regions->status = trim($request->input('status'));
    	$Regions->created_by = Auth::user()->id;
    	$res = $Regions->save();
    	if( $res ) {
    		return back()->with('msg', 'Region Added Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function regionEdit($id) {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'settings';
    	$dataBag['childMenu'] = 'regoSett';
    	$dataBag['continents'] = Continents::where('status', '=', '1')->orderBy('continents_name', 'asc')->get();
    	$dataBag['region'] = Regions::findOrFail($id);
    	return view('dashboard.regional_settings.region_add', $dataBag);
    }

    public function regionUpdate(Request $request, $id) {

    	$Regions = Regions::find($id);
    	$Regions->continent_id = trim($request->input('continent_id'));
    	$Regions->region_name = trim(ucfirst($request->input('region_name')));
    	$Regions->status = trim($request->input('status'));
    	$Regions->updated_by = Auth::user()->id;
    	$Regions->updated_at = date('Y-m-d H:i:s');
    	$res = $Regions->save();
    	if( $res ) {
    		return back()->with('msg', 'Region Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function regionDelete($id) {
    	$del = Regions::findOrFail($id);
    	$res = $del->delete();
    	if( $res ) {
    		return back()->with('msg', 'Region Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }


    /*** Country ***/

    public function countryList() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['allCountries'] = Countries::where('status', '!=', '3')->get();
        return view('dashboard.regional_settings.country_list', $dataBag);
    }

    public function countryAdd() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['continents'] = Continents::where('status', '=', '1')->orderBy('continents_name', 'asc')->get();
        return view('dashboard.regional_settings.country_add', $dataBag);
    }

    public function countrySave(Request $request) {

        $Countries = new Countries;
        $Countries->region_id = trim($request->input('region_id'));
        $Countries->country_name = trim(ucfirst($request->input('country_name')));
        $Countries->status = trim($request->input('status'));
        $Countries->created_by = Auth::user()->id;
        $Countries->lat = trim($request->input('lat'));
        $Countries->lng = trim($request->input('lng'));
        $res = $Countries->save();
        if( $res ) {
            return back()->with('msg', 'Country Added Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function countryEdit($id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['continents'] = Continents::where('status', '=', '1')->orderBy('continents_name', 'asc')->get();
        $editCountry = Countries::findOrFail($id);
        $dataBag['country'] = $editCountry;
        $continent_id = 0;
        if(!empty($editCountry)) {
            $continent_id = $editCountry->Region->continent_id;
        }
        $editRegions = Regions::where('continent_id', '=', $continent_id)->orderBy('region_name', 'asc')->get();
        $dataBag['regions'] = $editRegions;
        return view('dashboard.regional_settings.country_add', $dataBag);
    }

    public function countryUpdate(Request $request, $id) {

        $Countries = Countries::find($id);
        $Countries->region_id = trim($request->input('region_id'));
        $Countries->country_name = trim(ucfirst($request->input('country_name')));
        $Countries->status = trim($request->input('status'));
        $Countries->updated_by = Auth::user()->id;
        $Countries->updated_at = date('Y-m-d H:i:s');
        $Countries->lat = trim($request->input('lat'));
        $Countries->lng = trim($request->input('lng'));
        $res = $Countries->save();
        if( $res ) {
            return back()->with('msg', 'Country Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function countryDelete($id) {
        $del = Countries::findOrFail($id);
        $res = $del->delete();
        if( $res ) {
            return back()->with('msg', 'Country Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }


    /*** Provinces ***/

    public function provincesList() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['allProvinces'] = Provinces::where('status', '!=', '3')->get();
        return view('dashboard.regional_settings.provinces_list', $dataBag);
    }

    public function provincesAdd() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['countries'] = Countries::where('status', '=', '1')->orderBy('country_name', 'asc')->get();
        return view('dashboard.regional_settings.provinces_add', $dataBag);
    }

    public function provincesSave(Request $request) {
        $Provinces = new Provinces;
        $Provinces->country_id = trim($request->input('country_id'));
        $Provinces->province_name = trim(ucfirst($request->input('province_name')));
        $Provinces->status = trim($request->input('status'));
        $Provinces->created_by = Auth::user()->id;
        $res = $Provinces->save();
        if( $res ) {
            return back()->with('msg', 'Provinces Added Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function provincesEdit($id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['countries'] = Countries::where('status', '=', '1')->orderBy('country_name', 'asc')->get();
        $dataBag['province'] = Provinces::findOrFail($id);
        return view('dashboard.regional_settings.provinces_add', $dataBag);
    }

    public function provincesUpdate(Request $request, $id) {
        $Provinces = Provinces::find($id);
        $Provinces->country_id = trim($request->input('country_id'));
        $Provinces->province_name = trim(ucfirst($request->input('province_name')));
        $Provinces->status = trim($request->input('status'));
        $Provinces->updated_by = Auth::user()->id;
        $Provinces->updated_at = date('Y-m-d H:i:s');
        $res = $Provinces->save();
        if( $res ) {
            return back()->with('msg', 'Provinces Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        } 
    }

    public function provincesDelete($id) {
        $del = Provinces::findOrFail($id);
        $res = $del->delete();
        if( $res ) {
            return back()->with('msg', 'Provinces Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }


    /*** City ***/

    public function cityList() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['allCity'] = Cities::where('status', '!=', '3')->get();
        return view('dashboard.regional_settings.city_list', $dataBag); 
    }

    public function cityAdd() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['countries'] = Countries::where('status', '=', '1')->orderBy('country_name', 'asc')->get();
        return view('dashboard.regional_settings.city_add', $dataBag);
    }

    public function citySave(Request $request) {
        $Cities = new Cities;
        $Cities->province_id = trim($request->input('province_id'));
        $Cities->city_name = trim(ucfirst($request->input('city_name')));
        $Cities->status = trim($request->input('status'));
        $Cities->created_by = Auth::user()->id;
        $res = $Cities->save();
        if( $res ) {
            return back()->with('msg', 'City Added Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function cityEdit($id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'regoSett';
        $dataBag['countries'] = Countries::where('status', '=', '1')->orderBy('country_name', 'asc')->get();
        $editCity = Cities::findOrFail($id);
        $selectCountryID = $editCity->Province->Country->id;
        $selectedProvines = Provinces::where('country_id', '=', $selectCountryID)->get();
        $dataBag['city'] = $editCity;
        $dataBag['selectCountryID'] = $selectCountryID;
        $dataBag['selectedProvines'] = $selectedProvines;
        return view('dashboard.regional_settings.city_add', $dataBag);
    }

    public function cityUpdate(Request $request, $id) {
        $Cities = Cities::find($id);
        $Cities->province_id = trim($request->input('province_id'));
        $Cities->city_name = trim(ucfirst($request->input('city_name')));
        $Cities->status = trim($request->input('status'));
        $Cities->updated_by = Auth::user()->id;
        $Cities->updated_at = date('Y-m-d H:i:s');
        $res = $Cities->save();
        if( $res ) {
            return back()->with('msg', 'City Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function cityDelete($id) {
        $del = Cities::findOrFail($id);
        $res = $del->delete();
        if( $res ) {
            return back()->with('msg', 'City Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    /*** AJAX ***/

    public function ajaxRegionList(Request $request) {

        $data = array();
        if( $request->ajax() ) {
            $data = Regions::where('continent_id', '=', trim($request->input('continent_id')))->get()->toJson(); 
        }

        return $data;
    }

    public function ajaxProvinceList(Request $request) {

        $data = array();
        if( $request->ajax() ) {
            $data = Provinces::where('country_id', '=', trim($request->input('country_id')))->get()->toJson(); 
        }

        return $data;
    }

    public function ajaxCityList(Request $request) {

        $data = array();
        if( $request->ajax() ) {
            $data = Cities::where('province_id', '=', trim($request->input('province_id')))->get()->toJson(); 
        }

        return $data;
    }


    public function getContinentToCountry(Request $request) {

        $continent_id = trim($request->input('continent_id'));

        $data = DB::table('regions')->where('regions.continent_id', '=', $continent_id)
        ->join('countries', 'countries.region_id', '=', 'regions.id')
        ->orderBy('countries.country_name', 'asc')->select('countries.country_name as name', 'countries.id as id')
        ->get()->toJson();

        return $data;

    }


    public function getContinentToCity(Request $request) {

        $country_id = trim($request->input('country_id'));

        $data = DB::table('provinces')->where('provinces.country_id', '=', $country_id)
        ->join('cities', 'cities.province_id', '=', 'provinces.id')
        ->orderBy('cities.city_name', 'asc')->select('cities.city_name as name', 'cities.id as id')
        ->get()->toJson();

        return $data;
    }

}
