import express from "express";
import estocController from "../../controllers/estocController";
import producteController from "../../controllers/producteController";

/**
 * @swagger
 * components:
 *   schemas:
 *     ErrorResponseFK:
 *       type: object
 *       properties:
 *         status:
 *           type: string
 *           description: Status of the request
 *           example: "KO"
 *         error:
 *           type: object
 *           description: Error object
 *           properties:
 *             name:
 *               type: string
 *             parent:
 *               type: object
 *               properties:
 *                 errno:
 *                   type: integer
 *                 code:
 *                   type: string
 *                 sql:
 *                   type: string
 *             original:
 *               type: object
 *               properties:
 *                 errno:
 *                   type: integer
 *                 code:
 *                   type: string
 *                 sql:
 *                   type: string
 *             sql:
 *               type: string
 *             parameters:
 *               type: object
 *            
 *     ErrorResponse:
 *       type: object
 *       properties:
 *         status:
 *           type: string
 *           description: Status of the request
 *           example: "KO"
 *         error:
 *           type: object
 *           description: Error object
 * 
 *     ProducteAttributes:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           example: "3c0fea13-cdcd-4d70-8580-79f06c01cafe"
 *         nom:
 *           type: string
 *           example: "patates"
 *         tipus:
 *           type: string
 *           example: "carbohidrats"
 *         preu:
 *           type: string
 *           example: "15"
 *         categoria:
 *           type: string
 *           example: "9c0fe013-cdcF-4d70-8580-79f06c01cafe"
 *         createdAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *         updatedAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *     MaquinaAttributes:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           example: "acb11d49-1643-43a6-8389-3e4dc964bfe4"
 *         municipi:
 *           type: string
 *           example: "Barcelona"
 *         adreca:
 *           type: string
 *           example: "Carrer de la Ciutat de Granada, 1"
 *         createdAt:
 *           type: string
 *           example: "2023-02-20T15:24:48.000Z"
 *         updatedAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *     EstocAttributes:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *         producte:
 *           type: string
 *           example: "9c0fe013-cdcF-4d70-8580-79f06c01cafe"
 *         caducitat:
 *           type: string
 *           example: "2023-02-14"
 *         dataVenda:
 *           type: string
 *           example: "2023-02-14"
 *         ubicacio:
 *           type: string
 *           example: "9c0fe013-cdcF-4d70-8580-79f06c01cafe"
 *         createdAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *         updatedAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *     CategoriaAttributes:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           example: "9c0fe013-cdcF-4d70-8580-79f06c01cafe"
 *         nom:
 *           type: string
 *           example: "Carbohidrat"
 *         iva:
 *           type: string
 *           example: "24"
 *         createdAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *         updatedAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *     CalaixAttributes:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           example: "9c0fe013-cdcF-4d70-8580-79f06c01cafe"
 *         maquina:
 *           type: string
 *           example: "9c0fe013-cdcF-4d70-8580-79f06c01cafe"
 *         casella:
 *           type: string
 *           example: "3"
 *         createdAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 *         updatedAt:
 *           type: string
 *           example: "2023-02-20T14:26:45.245Z"
 * 
 * /api/v1/productes:
 *   get:
 *     summary: Get all productes
 *     tags:
 *       -  Productes
 *     description: Retrieve a list of all productes.
 *     responses:
 *       201:
 *         description: Successfully retrieved all productes.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                 allProductes:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/ProducteAttributes'
 *       400:
 *         description: Bad request.
 *         content:
 *           application/json: 
 *             schema: 
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                   example: "KO"
 *                 error:
 *                   type: string
 *                   description: Error message
 *                   example: "Invalid date format, use YYYY-MM-DD"
*       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *   post:
 *     summary: Insert producte
 *     tags:
 *       -  Productes
 *     description: Insert producte
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               nom:
 *                 type: string
 *                 description: Nom del producte.
 *                 example: "papes"
 *               tipus:
 *                 type: string
 *                 description: Tipus del producte.
 *                 example: "aliment"
 *               preu:
 *                 type: string
 *                 description: Preu del producte.
 *                 example: "20"
 *               categoria:
 *                 type: string
 *                 description: Id de la categoria del producte.
 *                 example: "261607ea-ada8-49bf-ba86-03a11ef2e21e"
 * 
 *     responses:
 *       201:
 *         description: Successfully inserted producte.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                 createdProducte:
 *                   $ref: '#/components/schemas/ProducteAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema:
 *               oneOf: 
 *                 - $ref: '#/components/schemas/ErrorResponse'
 *                 - $ref: '#/components/schemas/ErrorResponseFK'
 *       400:
 *         description: Bad request.
 *         content:
 *           application/json: 
 *             schema: 
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                   example: "KO"
 *                 error:
 *                   type: string
 *                   description: Error message
 *                   example: "Invalid date format, use YYYY-MM-DD"
 * 
 * /api/v1/productes/{producteId}:
 *   get:
 *     summary: Get a specific producte
 *     tags:
 *       -  Productes
 *     description: Retrieve a specific producte by ID.
 *     parameters:
 *       - in: path
 *         name: producteId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the producte to retrieve.
 *     responses:
 *       201:
 *         description: Successfully retrieved the producte.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                 producte:
 *                   $ref: '#/components/schemas/ProducteAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *   patch:
 *     summary: Update a specific producte
 *     tags:
 *       -  Productes
 *     description: Update a specific producte by ID.
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               nom:
 *                 type: string
 *                 description: Nom del producte.
 *                 example: "papes"
 *               tipus:
 *                 type: string
 *                 description: Tipus del producte.
 *                 example: "aliment"
 *               preu:
 *                 type: string
 *                 description: Preu del producte.
 *                 example: "20"
 *               categoria:
 *                 type: string
 *                 description: Id de la categoria del producte.
 *                 example: "261607ea-ada8-49bf-ba86-03a11ef2e21e"
 *     parameters:
 *       - in: path
 *         name: producteId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the producte to Update.
 *     responses:
 *       201:
 *         description: Successfully Updated the producte.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                   example: "OK"
 *                 message:
 *                   type: string
 *                   description: Message of the request
 *                   example: "Producte updated successfully"
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *   delete:
 *     summary: delete a specific producte
 *     tags:
 *       -  Productes
 *     description: delete a specific producte by ID.
 *     parameters:
 *       - in: path
 *         name: producteId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the producte to delete.
 *     responses:
 *       201:
 *         description: Successfully deleted the producte.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                   example: "OK"
 *                 message:
 *                   type: string
 *                   description: Message of the request
 *                   example: "Producte deleted successfully"
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 * /api/v1/productes/{producteId}/estocs:
 *   get:
 *     summary: Get stock information for a producte.
 *     tags:
 *       -  Productes
 *     description: Returns an array of objects containing information about the stock of each producte.
 *     parameters:
 *       - in: path
 *         name: producteId
 *         description: The ID of the producte to retrieve stock information for.
 *         required: true
 *         schema:
 *           type: string
 *       - in: query
 *         name: disponible
 *         schema:
 *           type: boolean
 *         required: false
 *         allowEmptyValue: true
 *         description: Retorna tots els estocs disponibles d'un producte.
 *     responses:
 *       '200':
 *         description: Successful response containing an array of stock information objects.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Indicates the status of the response. Will always be "OK".
 *                 estocs:
 *                   type: array
 *                   description: An array of objects containing stock information for each producte.
 *                   items:
 *                     $ref: '#/components/schemas/ProducteAttributes'
 * 
 *
*/
const router = express.Router();

router.get("/", producteController.getAllProductes)

router.get("/:producteId", producteController.getOneProducte)
router.post("/", producteController.createNewProducte)
router.patch("/:producteId", producteController.updateOneProducte)
router.delete("/:producteId", producteController.deleteOneProducte)

    .get("/:producteId/estocs", estocController.getEstocsForProducte);
export default router;