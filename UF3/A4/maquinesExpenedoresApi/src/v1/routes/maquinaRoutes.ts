import express from "express";
import maquinaController from "../../controllers/maquinaController";

/**
 * @swagger
 * components:
 *   schemas:
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
 * /api/v1/maquines:
 *   get:
 *     summary: Get all maquines
 *     tags:
 *       -  Maquines
 *     description: Retrieve a list of all maquines.
 *     responses:
 *       201:
 *         description: Successfully retrieved all maquines.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                   example: "OK"
 *                 allMaquines:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/MaquinaAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 * /api/v1/maquines/{maquinaId}:
 *   get:
 *     summary: Get a specific maquina
 *     tags:
 *       -  Maquines
 *     description: Retrieve a specific maquina by ID.
 *     parameters:
 *       - in: path
 *         name: maquinaId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the maquina to retrieve.
 *     responses:
 *       201:
 *         description: Successfully retrieved the maquina.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                   example: "OK"
 *                 maquina:
 *                   $ref: '#/components/schemas/MaquinaAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *
 * /api/v1/maquines/{maquinaId}/estocs:
 *   get:
 *     summary: Get stock information for a vending machine.
 *     tags:
 *       -  Maquines
 *     description: Returns an array of objects containing information about the stock stored in each compartment of the specified vending machine.
 *     parameters:
 *       - name: maquinaId
 *         in: path
 *         description: The ID of the vending machine to retrieve stock information for.
 *         required: true
 *         schema:
 *           type: string
 *       - in: query
 *         name: disponible
 *         schema:
 *           type: boolean
 *         required: false
 *         allowEmptyValue: true
 *         description: Retorna tots els estocs disponibles d'una maquina.
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
 *                 calaixos:
 *                   type: array
 *                   description: An array of objects containing stock information for each compartment of the vending machine.
 *                   items:
 *                     type: object
 *                     properties:
 *                       id:
 *                         type: string
 *                         description: The ID of the compartment.
 *                       maquina:
 *                         type: string
 *                         description: The ID of the vending machine that the compartment belongs to.
 *                       casella:
 *                         type: string
 *                         description: The location of the compartment in the vending machine.
 *                       createdAt:
 *                         type: string
 *                         format: date-time
 *                         nullable: true
 *                         description: The date and time when the compartment was created.
 *                       updatedAt:
 *                         type: string
 *                         format: date-time
 *                         nullable: true
 *                         description: The date and time when the compartment was last updated.
 *                       estocs:
 *                         type: array
 *                         description: An array of objects containing information about the products stored in the compartment.
 *                         items:
 *                           type: object
 *                           properties:
 *                             id:
 *                               type: string
 *                               description: The ID of the stock item.
 *                             producte:
 *                               type: string
 *                               description: The name of the product stored in the compartment.
 *                             caducitat:
 *                               type: string
 *                               format: date
 *                               description: The expiration date of the product stored in the compartment.
 *                             dataVenda:
 *                               type: string
 *                               format: date
 *                               nullable: true
 *                               description: The date and time when the product was sold. Will be null if the product has not been sold.
 *                             ubicacio:
 *                               type: string
 *                               description: The ID of the compartment that the product is stored in.
 *                             createdAt:
 *                               type: string
 *                               format: date-time
 *                               nullable: true
 *                               description: The date and time when the stock item was created.
 *                             updatedAt:
 *                               type: string
 *                               format: date-time
 *                               nullable: true
 *                               description: The date and time when the stock item was last updated.
 *
 *  
 * /api/v1/maquines/{maquinaId}/calaixos:
 *   get:
 *     summary: Get calaixos information for a vending machine.
 *     tags:
 *       -  Maquines
 *     description: Returns an array of objects containing information about the calaixos stored in each maquina.
 *     parameters:
 *       - name: maquinaId
 *         in: path
 *         description: The ID of the vending machine to retrieve calaixos information for.
 *         required: true
 *         schema:
 *           type: string
 *       - in: query
 *         name: buits
 *         schema:
 *           type: boolean
 *         required: false
 *         allowEmptyValue: true
 *         description: Retorna tots els calaixos buits d'una maquina.
 *     responses:
 *       '200':
 *         description: Successful response containing an array of calaixos information objects.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Indicates the status of the response. Will always be "OK".
 *                 calaixos:
 *                   type: array
 *                   description: An array of objects containing stock information for each compartment of the vending machine.
 *                   items:
 *                     $ref: '#/components/schemas/CalaixAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 */
const router = express.Router();

router

.get("/", maquinaController.getAllMaquines)
.get("/:maquinaId", maquinaController.getOneMaquina)

.get("/:maquinaId/estocs", maquinaController.getEstocsForMaquina)
.get("/:maquinaId/calaixos", maquinaController.getCalaixosForMaquina);
export default router;