import {ProducteDb} from "../database/Producte";

const getAllProductes = async () => {
    const productes = await ProducteDb.findAll({where:{}});
    return productes;
};

const getOneProducte = async (id:any) => {
    const producte = await ProducteDb.findOne({where:{id:id}});
    return producte;
    
};

const createNewProducte = async (producteJSON:any) => {
    const producte = await ProducteDb.create({...producteJSON});
    return producte;
};

const updateOneProducte = async (producteId:any, canvis:any) => {
    const producte = await ProducteDb.update(canvis, {where:{id:producteId}});
    return producte;
};

const deleteOneProducte = async (producteId:any) => {
    await ProducteDb.destroy({where:{id:producteId}});
    return;
};

export default {
    getAllProductes,
    getOneProducte,
    createNewProducte,
    updateOneProducte,
    deleteOneProducte,
};