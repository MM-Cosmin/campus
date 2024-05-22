<?php

namespace Modules\Gmeet\Repositories\Interfaces;

interface GoogleAccountRepositoryInterface

{
    public function store($request, $google);
    public function destroy($googleAccount, $google);
}
