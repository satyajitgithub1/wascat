<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item_type;
use DB;
use App\User;

class Item_typecontroller extends Controller
{
    public function index()
    {
    	$all_info = DB::table('item_types')->paginate(10);

    	return view('admin.master.item_type',['all_info'=>$all_info]);
    }

    public function save(Request $request)
    {
        $this->validate($request,['itemtype'=>'required']);
        $image = $request->file('signature');

        $img_name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/upload');

        $image->move($destinationPath, $img_name);

    	$save_date = new Item_type();
    	$save_date->name = $request->itemtype;
        $save_date->image = $img_name;

    	$save_date->save();
    	return redirect('/item_type')->with('meg','Your data save successfully');
    	
    }

    public function ajaxCall(Request $request)
    {
        $data['id'] = $request->id;

        echo json_encode($data);
         
       //echo $request->id;die;

    }

    public function edit($id)
    {
      $per_info = DB::table('item_types')->where('id', $id)->first();

      $all_info = DB::table('item_types')->paginate(2);
      return view('admin.master.item_type_edit',['all_info'=>$all_info,'per_info'=>$per_info,'active'=>2]);

    }

    public function update(Request $request)
    {
        $per_info = DB::table('item_types')->where('id', $request->id)->first();
        $img_name = $per_info->image;

        $image = $request->file('signature');

        $destinationPath = public_path('/upload');

        $image->move($destinationPath, $img_name);


        $save_date = Item_type::find($request->id);
        $save_date->name = $request->itemtype;
        $save_date->save();
        return redirect('/item_type')->with('meg','Your data edit successfully');
    }

    public function delete($id)
    {

        $delete_var = Item_type::findOrFail($id);

        $delete_var->delete();
        return redirect('/item_type')->with('meg','Your recode is successfully deleted');
      
    }
}
