import { Op } from "sequelize";
import { TascaDb } from "../database/Tasca";
import { UsuariDb } from "../database/Usuari";

const getAllUsuaris = async (filters:any) => {
    const usuaris = await UsuariDb.findAll({where:filters});
    return usuaris;
};

const createNewUsuari = async (usuariJSON:any) => {
    const producte = await UsuariDb.create({...usuariJSON});
    return producte;
};

const deleteOneUsuari = async (usuariId:any) => {
    await UsuariDb.destroy({where:{id:usuariId}});
    return;
};

const getTasquesFromUsuari = async (filters:any) => {
    let usuaris:any = [];
    if(filters.status){

        usuaris = await UsuariDb.findAll({
            where:{id:filters.usuariId},
            include: [
                { model: TascaDb, required: false 
                    ,where:{status:filters.status}
                },
            ],
            
            
            
        });

    }else if (filters.createdAt){
        usuaris = await UsuariDb.findAll({
            where:{id:filters.usuariId},
            include: [
                { model: TascaDb, required: false 
                    ,where:{
                        
                        
                        createdAt:{
                            [Op.gt]:filters.createdAt,
                        },
                    
                    
                    }
                },
            ],
            
            
            
        });
    }else if(filters.status && filters.createdAt){
        usuaris = await UsuariDb.findAll({
            where:{id:filters.usuariId},
            include: [
                { model: TascaDb, required: false 
                    ,where:{
                        
                        
                        createdAt:{
                        [Op.gt]:filters.createdAt,
                        },
                        
                        
                        status:filters.status}
                },
            ],
            
            
            
        });
    }else{
        usuaris = await UsuariDb.findAll({
            where:{id:filters.usuariId},
            include: [
                { model: TascaDb, required: false },
            ],
            
            
            
        });
    }
    
    
    return usuaris;
};

export default {
    getAllUsuaris,
    createNewUsuari,
    deleteOneUsuari,
    getTasquesFromUsuari,
};