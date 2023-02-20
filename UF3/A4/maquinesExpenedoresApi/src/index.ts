import express from "express";
import v1ProducteRouter from "./v1/routes/producteRoutes";
import v1EstocRouter from "./v1/routes/estocRoutes";
import v1MaquinaRouter from "./v1/routes/maquinaRoutes";
import db from "./config/database.config";
import bodyParser from "body-parser";
import createAssociations from "./database/Associations";
import {V1SwaggerDocs} from "./v1/swagger";

//Creo les claus foranes
db.beforeSync(() => createAssociations());
db.sync().then(() => {
  console.log("Database is connected");
});

const app = express();
const PORT = process.env.PORT || 3000;

app.use(bodyParser.json());
app.use("/api/v1/productes", v1ProducteRouter);
app.use("/api/v1/estocs", v1EstocRouter);
app.use("/api/v1/maquines", v1MaquinaRouter);


app.listen(PORT, () => {
  console.log(`API is listening on port ${PORT}`);
  V1SwaggerDocs.swaggerDocs(app, PORT);
});

