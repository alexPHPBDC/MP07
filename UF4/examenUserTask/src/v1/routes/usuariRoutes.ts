import express from "express";
import usuariController from "../../controllers/usuariController";
import estocController from "../../controllers/usuariController";



const router = express.Router();

router.get("/", usuariController.getAllUsuaris)
.get("/:usuariId/tasques", usuariController.getTasquesFromUsuari)
.post("/", usuariController.createNewUser)

.delete("/:usuariId", usuariController.deleteOneUsuari)
//.get("/:usuariId/tasques", usuariController.getTasksFromUser);
export default router;
