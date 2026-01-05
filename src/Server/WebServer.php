<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Elements\Document;
use Websyspro\Core\Elements\Dom;
use Exception;
use Websyspro\Core\Util;

/**
 * Core HTTP server class for routing and request handling.
 *
 * This class provides a minimalistic framework for defining HTTP routes,
 * handling requests, executing route handlers, and returning structured
 * JSON responses with proper HTTP status codes.
 *
 * It supports both CLI execution (for debugging/logging) and standard
 * API requests.
 */
class WebServer extends AbstractServer
{
  /**
   * Ensures that at least one route was matched.
   *
   * If no routers are available after applying all filters,
   * this method triggers a "Not Found" error response.
   */ 
  public function routersEmpty(
  ): void {
    if( Util::exist( array: $this->routers ) === false ){
      // TO DO
    }
  }

  /**
   * Executes the matched router for the current request.
   *
   * This method resolves the first matched route and determines how it
   * should be executed based on its type:
   *
   * - RouteDirect: Executes a user-defined callable directly, passing
   *   the response object and resolved request parameters.
   * - Router: Delegates execution to a controller/action pipeline and
   *   serializes the returned value as a JSON response.
   *
   * This method represents the final execution step of the routing
   * pipeline, occurring after route matching and request parameter
   * resolution.
   */
  public function routersHtml(
    object|string $baseView
  ): void {
    if( Util::exist( array: $this->routers )){
      /**
       * Retrieves the first matched router 
       * */
      [ $router ] = $this->routers;

      /**
       * Direct route execution (closure or callable handler)
       * */
      if( $router instanceof RouteDirect ){
        if( Util::isFN( fn: $router->handler )){

          /* 
          * Executes the user-defined handler, passing the response instance
          * and resolved parameters based on the request URI
          **/
          // Util::callUserFN( fn: $router->handler, args: [
          //   $this->response(), $this->request()->defineParam(
          //     requestUri: $router->uri()
          //   )
          // ]);
        }
      }
      
      /**
       * Controller-based route execution  
       **/
      if( $router instanceof Router ) {
        /** 
         * Executes the controller action and serializes the result as JSON 
         * */
        // $route = $router->execute( 
        //   request: $this->request()->defineParam(
        //     requestUri: $router->uri()
        //   )
        // );

        // $this->response()->json(
        //   value: $router->execute( 
        //     request: $this->request()->defineParam(
        //       requestUri: $router->uri()
        //     )
        //   )
        // );
      }
    }



    Document::render( 
      [
        Dom::docType(),
        Dom::html()->add( [
          Dom::head()->add( 
            [
              Dom::title( "Websyspro" )
            ]
          ),
          Dom::body()->add( 
            [
              $baseView::render(
                
              )
            ]
          )
        ])
      ]
    );    
  }

  public function bootstrap(
    object|string $baseView
  ): void {
    try {
      $this->routersByMethods();
      $this->routersByUris();
      //$this->routersEmpty();
      $this->routersHtml(
        $baseView
      );
    } catch ( Exception $error ){
      $this->routersIsError(
        $error
      );
    }
  }  
}