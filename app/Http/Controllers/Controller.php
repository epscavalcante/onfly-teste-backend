<?php

namespace App\Http\Controllers;


/**
 * @OA\Info(
 *    title="OnFly - Api docs",
 *    version="1.0.0",
 *    @OA\Contact(
 *      name="Eduardo Cavalcante",
 *      url="https://epscavalcante.dev",
 *      email="eduardo.ps.cavalcante@gmail.com"
 *    )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearer_token",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
