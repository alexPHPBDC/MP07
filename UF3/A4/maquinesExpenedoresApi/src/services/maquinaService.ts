
import { MaquinaDb } from "../database/Maquina";
import { CalaixDb } from "../database/Calaix";
import { EstocDb } from "../database/Estoc";
import { Op } from "sequelize";


const getAllMaquines = async () => {
    const maquines = await MaquinaDb.findAll({ where: {} });
    return maquines;
};

const getOneMaquina = async (id: any) => {
    const maquina = await MaquinaDb.findOne({ where: { id: id } });
    return maquina;
};

const getCalaixosForMaquina = async (filters: any) => {

    let calaixos: CalaixDb[];

    if (filters.buits) {
        calaixos = await CalaixDb.findAll({
            where: {
                [Op.and]: [
                    {
                        maquina: filters.maquina
                    },
                    {
                        '$Estocs.ubicacio$': { [Op.is]: null }
                    }
                ]
            },
            include: [
                { model: EstocDb, required: false },

            ],
        })
    } else {
        calaixos = await CalaixDb.findAll(
            {
                where: {

                    maquina: filters.maquina
                }
            });
    }

    return calaixos;
}

const getEstocsForMaquina = async (filters: any) => {

    let estoc = null;

    if (filters.disponible) {
        estoc = await CalaixDb.findAll({
            where: {
                [Op.and]: [
                    { maquina: filters.maquina },
                    { '$Estocs.dataVenda$': { [Op.is]: null } }
                ]
            },
            include: [
                { model: EstocDb, required: true },
            ],
        })

    } else {
        estoc = await CalaixDb.findAll({
            where: {
                maquina: filters.maquina
            },
            include: [
                { model: EstocDb, required: false },
            ],
        });
    }

    return estoc;
};

export default {
    getAllMaquines,
    getOneMaquina,
    getCalaixosForMaquina,
    getEstocsForMaquina
};