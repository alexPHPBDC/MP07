
import { UsuariDb } from "../database/Usuari";
import { Op } from "sequelize";
import { TascaDb } from "../database/Tasca";

const getOneTasca = async (id: any) => {
    const tasca = await TascaDb.findOne({ where: { id: id } });
    return tasca;
};

const createNewTasca = async (tascaJSON:any) => {
    const tasca = await TascaDb.create({...tascaJSON});
    return tasca;
};

const updateOneTasca = async (tascaId:any, canvis:any) => {
    const tasca = await TascaDb.update(canvis, {where:{id:tascaId}});
    return tasca;
};

const deleteOneTasca = async (tascaId:any) => {
    await TascaDb.destroy({where:{id:tascaId}});
    return;
};

export default {
    updateOneTasca,
    getOneTasca,
    createNewTasca,
    deleteOneTasca,
};