<?php

/**
 * @api {post} /auth.php Login or Register a User
 * @apiName AuthPost
 * @apiGroup Auth
 * @apiDescription Handles login, registration, or token validation based on the provided request body.
 *
 * @apiParam (Login) {String} email User's email.
 * @apiParam (Login) {String} password User's password.
 *
 * @apiParam (Register) {String} nom Last name.
 * @apiParam (Register) {String} prenom First name.
 * @apiParam (Register) {String} equipe Team name.
 * @apiParam (Register) {String} email User's email.
 * @apiParam (Register) {String} password Password.
 * @apiParam (Register) {String} confirmpassword Must match password.
 *
 * @apiParam (Token Validation) {String} token JWT token to validate.
 *
 * @apiSuccess (200 OK) {String} response OK
 * @apiSuccess (200 OK) {String} [token] JWT token (if login or token is refreshed)
 * @apiSuccess (200 OK) {Boolean} [valid] true if token is valid (only on validation)
 *
 * @apiError (400 Bad Request) {String} response Error message
 * @apiError (405 Method Not Allowed) {String} response Token is invalid
 */

/**
 * @api {put} /auth.php Refresh JWT Token
 * @apiName RefreshToken
 * @apiGroup Auth
 * @apiDescription Refreshes the JWT token expiration time if the token is valid.
 *
 * @apiParam {String} token JWT token to refresh.
 *
 * @apiSuccess (200 OK) {String} response OK
 * @apiSuccess (200 OK) {String} token Refreshed JWT token
 *
 * @apiError (400 Bad Request) {String} response Please provide a proper data
 * @apiError (405 Method Not Allowed) {String} response Token is invalid
 */

/**
 * @api {options} /auth.php Preflight (CORS)
 * @apiName AuthOptions
 * @apiGroup Auth
 * @apiDescription Handles CORS preflight request.
 *
 * @apiSuccess (200 OK) {String} response Options ok
 * @apiSuccess (200 OK) {Array} data Empty array
 */
