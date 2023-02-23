import express from "express";
import tascaController from "../../controllers/tascaController";

const router = express.Router();

router
.get("/:tascaId", tascaController.getOneTasca)
.post("/", tascaController.createNewTasca)
.patch("/:tascaId", tascaController.updateOneTasca)
.delete("/:tascaId", tascaController.deleteOneTasca);
export default router;