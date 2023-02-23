import express from "express";
import v1TascaRouter from "./v1/routes/tascaRoutes";
import v1UsuariRouter from "./v1/routes/usuariRoutes";
import db from "./config/database.config";
import bodyParser from "body-parser";
import createAssociations from "./database/Associations";

//Creo les claus foranes
db.beforeSync(() => createAssociations());
db.sync().then(() => {
  console.log("Database is connected");
});

const app = express();
const PORT = process.env.PORT || 3000;

app.use(bodyParser.json());
app.use("/api/v1/usuaris", v1UsuariRouter);
app.use("/api/v1/tasques", v1TascaRouter);

app.listen(PORT, () => {
  console.log(`API is listening on port ${PORT}`);
});

