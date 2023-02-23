
import { UsuariDb } from "./Usuari";
import { TascaDb } from "./Tasca";

export default function createAssociations(){
UsuariDb.hasMany(TascaDb, { foreignKey: 'user',
onDelete: 'RESTRICT'
});
TascaDb.belongsTo(UsuariDb, { foreignKey: 'user' });

}