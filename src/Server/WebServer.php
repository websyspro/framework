<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Elements\Document;
use Websyspro\Core\Elements\Dom;
use Exception;
use Websyspro\Core\Elements\Shareds\Component;
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
  private string $baseView;

  /**
   * Ensures that at least one route was matched.
   *
   * If no routers are available after applying all filters,
   * this method triggers a "Not Found" error response.
   */ 
  public function routersEmpty(
  ): void {
    if( Util::exist( array: $this->routers ) === false ){
      $this->applyContentHtml(
        $this->baseView::render(
          Dom::div()->add(
            [
              "Not found"
            ]
          )
        )
      );
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
  ): void {
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
        $this->applyContentHtml(
          $this->baseView::render(
            Util::callUserFN( fn: $router->handler, args: [
              $this->request()->defineParam(
                requestUri: $router->uri()
              )
            ])
          )
        );          
      }
    }
    
    /**
     * Controller-based route execution  
     **/
    if( $router instanceof Router ) {
      /** 
       * Executes the controller action and serializes the result as JSON 
       * */
      $this->applyContentHtml(
        $this->baseView::render(
          $router->execute( 
            request: $this->request()->defineParam(
              requestUri: $router->uri()
            )
          )
        )
      );
    }
  }

  /**
   * Renders the full HTML document with the given base view.
   *
   * This method constructs a complete HTML structure including:
   * - Doctype declaration
   * - <html> element
   * - <head> with a <title>
   * - <body> containing the provided base view component
   *
   * @param Component $baseView The main component to render inside the body
   *
   * @return void
   */  
  public function applyContentHtml(
    Component $baseView
  ): void {
    Document::render(
      [
        /**
         * Add the DOCTYPE declaration
         * **/
        Dom::docType(),

        /**
         * Create the HTML element with head and body
         * **/
        Dom::html()->add( [
          /**
           * Head section containing the base view component
           * **/
          Dom::head()->add( 
            [
              Dom::title( "Websyspro" )
            ]
          ),

          /**
           * Body section containing the base view component
           * **/
          Dom::body()->add( 
            [ $baseView ]
          )
        ])
      ]
    ); 
  }

  /**
   * Initializes the application base view.
   *
   * Sets the base view path or identifier used
   * as the default layout for rendering views.
   *
   * @param string $baseView Base view reference
   *
   * @return void
   */  
  public function bootstrap(
    string $baseView
  ): void {
    $this->baseView = $baseView;
  }   

  /**
   * Loads and processes all registered routes.
   *
   * Executes route resolution by HTTP method and URI,
   * handles empty and HTML routes, and captures any
   * exception to be processed as a routing error.
   *
   * @return void
   */  
  public function load(
  ): void {
    try {
      /**
       * Resolve routes by HTTP methods 
       * **/
      $this->routersByMethods();

      /**
       * Resolve routes by request URIs
       * **/
      $this->routersByUris();

      /**
       * Handle routes with empty definitions
       * **/
      $this->routersEmpty();

      /**
       * Handle routes that return HTML responses
       * **/
      $this->routersHtml();
    } catch ( Exception $error ){
      /**
       * Handle routing errors and exceptions
       * **/
      $this->routersIsError( $error );
    }
  }  
}