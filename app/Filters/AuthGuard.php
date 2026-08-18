<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('adminLoggedIn'))
        {
            return redirect()
                ->to('/' . env('ADMIN_PANEL_SLUG'));
        }

        // Panel administracyjny: operacje takie jak upload/menedzer plikow, eksport
        // czy import bywaja dlugie. Front ma krotki limit (.user.ini = 120 s), zeby
        // boty nie blokowaly workerow; tu — dla ZALOGOWANEGO admina — podnosimy go.
        set_time_limit(600);
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}