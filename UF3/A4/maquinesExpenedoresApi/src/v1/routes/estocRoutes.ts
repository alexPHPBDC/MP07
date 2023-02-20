import express from "express";
import estocController from "../../controllers/estocController";



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
 * /api/v1/estocs:
 *   get:
 *     summary: Get all estocs
 *     tags:
 *       -  Estocs
 *     description: Retrieve a list of all estocs.
 * 
 *     parameters:
 *       - in: query
 *         name: disponible
 *         schema:
 *           type: boolean
 *         required: false
 *         allowEmptyValue: true
 *         description: Retorna tots els estocs disponibles d'una maquina.
 *       - in: query
 *         name: venda
 *         schema:
 *           type: string
 *         required: false
 *         allowEmptyValue: true
 *         description: Retorna tots els estocs que s'han venut a la data indicada.
 *     responses:
 *       201:
 *         description: Successfully retrieved all estocs.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                 allEstocs:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/EstocAttributes'
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
 *     summary: Insert estoc
 *     tags:
 *       -  Estocs
 *     description: Insert estoc
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               producte:
 *                 type: string
 *                 description: id del producte.
 *                 example: "61064470-3547-47ae-88df-caf8e1ba7a8a"
 *               caducitat:
 *                 type: string
 *                 description: caducitat del producte.
 *                 example: "2023-02-10"
 *               ubicacio:
 *                 type: string
 *                 description: Id de la ubicacio del producte.
 *                 example: "bf76835b-76b3-4ecd-ab39-192c228e24ab"
 *               categoria:
 *                 type: string
 *                 description: UUID de la categoria a la qual pertany el producte.
 *                 example: "edc5a60d-0adf-42fe-b45e-f765fa925c8a"
 *     responses:
 *       201:
 *         description: Successfully inserted estoc.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                 createdEstoc:
 *                   $ref: '#/components/schemas/EstocAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
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
 * /api/v1/estocs/{estocId}:
 *   get:
 *     summary: Get a specific estoc
 *     tags:
 *       -  Estocs
 *     description: Retrieve a specific estoc by ID.
 *     parameters:
 *       - in: path
 *         name: estocId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the estoc to retrieve.
 *     responses:
 *       201:
 *         description: Successfully retrieved the estoc.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   description: Status of the request
 *                 estoc:
 *                   $ref: '#/components/schemas/EstocAttributes'
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *   patch:
 *     summary: Update a specific estoc
 *     tags:
 *       -  Estocs
 *     description: Update a specific estoc by ID.
 *     requestBody:
 *       required: true
 *       content:
 *         application/json:
 *           schema:
 *             type: object
 *             properties:
 *               producte:
 *                 type: string
 *                 description: id del producte.
 *                 example: "61064470-3547-47ae-88df-caf8e1ba7a8a"
 *               caducitat:
 *                 type: string
 *                 description: caducitat del producte.
 *                 example: "2023-02-10"
 *               ubicacio:
 *                 type: string
 *                 description: Id de la ubicacio del producte.
 *                 example: "bf76835b-76b3-4ecd-ab39-192c228e24ab"
 *               categoria:
 *                 type: string
 *                 description: UUID de la categoria a la qual pertany el producte.
 *                 example: "edc5a60d-0adf-42fe-b45e-f765fa925c8a"
 *     parameters:
 *       - in: path
 *         name: estocId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the estoc to Update.
 *     responses:
 *       201:
 *         description: Successfully Update the estoc.
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
 *                   example: "Estoc updated successfully"
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *   delete:
 *     summary: delete a specific estoc
 *     tags:
 *       -  Estocs
 *     description: delete a specific estoc by ID.
 *     parameters:
 *       - in: path
 *         name: estocId
 *         schema:
 *           type: string
 *         required: true
 *         description: ID of the estoc to delete.
 *     responses:
 *       201:
 *         description: Successfully delete the estoc.
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
 *                   example: "Estoc deleted successfully"
 *       500:
 *         description: Internal server error.
 *         content:
 *           application/json: 
 *             schema: 
 *               $ref: '#/components/schemas/ErrorResponse'
 *
 */
const router = express.Router();
router
.get("/", estocController.getAllEstocs)
.post("/", estocController.createNewEstoc)
.get("/:estocId", estocController.getOneEstoc)
.patch("/:estocId", estocController.updateOneEstoc)
.delete("/:estocId", estocController.deleteOneEstoc);

export default router;