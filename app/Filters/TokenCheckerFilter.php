<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\APITokenModel;

class TokenCheckerFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pass Login Page (Not Secure I think, because if someone pass this parameter from another page, can pass this authentication step)
        if (isset($_POST['login']) && $_POST['login'])
            return true;

        if (isset($_GET['user_id']) && $_GET['user_id'] > 0 && isset($_GET['token']) && $_GET['token'] != '')
        {
            $user_id = $_GET['user_id'];
            $token   = $_GET['token'];

            $apiTokenModel = new APITokenModel();

            $result = $apiTokenModel->where('user_id', $user_id)->orderBy('created_at', 'desc')->first();

            if (!empty($result))
            {
                if ($result['token'] != $token)
                {
                    die(json_encode('Token Expired', JSON_UNESCAPED_UNICODE));
                }
                if (strtotime($result['expiration_date']) < strtotime('-1 days'))
                {
                    die(json_encode('Token Expired', JSON_UNESCAPED_UNICODE));
                }
                
                return true;
            }
            die(json_encode('Log in first or use correct token', JSON_UNESCAPED_UNICODE));
        }
        else
        {
            die(json_encode('Log in first or use token', JSON_UNESCAPED_UNICODE));
        }
        
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}