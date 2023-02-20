import { CalaixDb } from "./Calaix";
import { EstocDb } from "./Estoc";
import { MaquinaDb } from "./Maquina";

export default function createAssociations(){
MaquinaDb.hasMany(CalaixDb, { foreignKey: 'maquina' });
CalaixDb.belongsTo(MaquinaDb, { foreignKey: 'maquina' });
CalaixDb.hasMany(EstocDb, { foreignKey: 'ubicacio' });
EstocDb.belongsTo(CalaixDb, { foreignKey: 'ubicacio' });
}