<?php namespace Witooh\Message;

use Illuminate\Support\ServiceProvider;
use Response;
use Witooh\Message\Exceptions\AuthenticateException;
use Witooh\Message\Exceptions\NotFoundException;
use Witooh\Message\Exceptions\PermissionException;
use Witooh\Message\Exceptions\ValidateException;

class MessageServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton('message-response', 'Witooh\Message\Message');

        $this->app->error(
            function (Symfony\Component\HttpKernel\Exception\HttpException $e, $code) {
                $headers = $e->getHeaders();

                switch ($code) {
                    case 401:
                        $default_message             = 'Invalid API key';
                        $headers['WWW-Authenticate'] = 'Basic realm="CRM REST API"';
                        break;

                    case 403:
                        $default_message = 'Insufficient privileges to perform this action';
                        break;

                    case 404:
                        $default_message = 'The requested resource was not found';
                        break;

                    default:
                        $default_message = 'An error was encountered';
                }

                return Response::json(
                    array(
                        'error' => $e->getMessage() ? : $default_message
                    ),
                    $code,
                    $headers
                );
            }
        );

        $this->app->error(
            function (ValidateException $e, $code) {
                return Response::json($e->getMessages(), $code);
            }
        );

        $this->app->error(
            function (AuthenticateException $e, $code) {
                return Response::json($e->getMessage(), $e->getCode());
            }
        );

        $this->app->error(
            function (NotFoundException $e, $code) {
                return Response::json($e->getMessage(), $e->getCode());
            }
        );

        $this->app->error(
            function (PermissionException $e, $code) {
                return Response::json($e->getMessage(), $e->getCode());
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}