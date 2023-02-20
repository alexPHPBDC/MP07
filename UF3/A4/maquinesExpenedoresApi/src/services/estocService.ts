import { v4 as uuid } from "uuid";
import {EstocDb} from "../database/Estoc";

const getAllEstocs = async (filter:any) => {
    console.log(filter);
    const estocs = await EstocDb.findAll({where:filter});
    return estocs;
};

const getOneEstoc = async (id:any) => {
    const estoc = await EstocDb.findOne({where:{id:id}});
    return estoc;
};

const getEstocsForProducte = async (filters:any) => {
    const estoc = await EstocDb.findAll({where:filters});
    return estoc;
};

const createNewEstoc = async (estocJSON:any) => {
    const id = uuid();
    const estoc = await EstocDb.create({...estocJSON, id});
    return estoc;
};

const updateOneEstoc = async (estocId:any, canvis:any) => {
    const estoc = await EstocDb.update(canvis, {where:{id:estocId}});
    return estoc;
};

const deleteOneEstoc = async (estocId:any) => {
    await EstocDb.destroy({where:{id:estocId}});
    return;
};

export default {
    getAllEstocs,
    getOneEstoc,
    createNewEstoc,
    updateOneEstoc,
    deleteOneEstoc,
    getEstocsForProducte,
};