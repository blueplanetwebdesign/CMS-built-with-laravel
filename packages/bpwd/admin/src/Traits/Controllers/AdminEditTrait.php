<?php

namespace Bpwd\Admin\Traits\Controllers;

use Redirect;

trait AdminEditTrait{
    

    public function getStoreRedirect($request, $flash_message, $task, $model)
    {
        if($task == 'save') return Redirect::to($request->url().'/'.$model->id.'/edit')->with([
            'flash_message'=> $flash_message,
            'flash_message_important' => true
        ]);
        elseif ($task == 'save-and-close') return Redirect::to($request->url())->with('flash_message', $flash_message);
        elseif ($task == 'save-and-new') return Redirect::to($request->url().'/create')->with('flash_message', $flash_message);
        elseif ($task == 'save-as-copy') return Redirect::to($request->url().'/'.$model->id.'/edit')->with('flash_message', $flash_message);

    }

}