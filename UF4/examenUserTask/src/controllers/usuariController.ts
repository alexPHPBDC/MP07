import { Request, Response } from "express";
import { Op } from "sequelize";
import usuariService from "../services/usuariService";
import { v4 as uuid } from "uuid";

const getAllUsuaris = async (req: Request, res: Response) => {
  let filters: any = {};
  const estat: any = req.query.estat;

  //TODO ESTAT is TODO, DOING OR DONE

  if (estat ) {

    filters = { estat: estat };
  }
  
  usuariService.getAllUsuaris(filters).then((allUsuaris:any) => {
    res.status(200).json({ status: "OK", allUsuaris });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const getTasquesFromUsuari = async (req: Request, res: Response) => {
  let filters: any = {};
  const estat: any = req.query.estat;
  const dataCreacio:any = req.query.dataCreacio;
  const estats = ["TODO", "DOING", "DONE"];
  if(estat && estats.includes(estat)){
    filters.status = estat ;
  }
  if(dataCreacio){
    const date = new Date(dataCreacio);
    if(isNaN(date.getTime()) || !/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(dataCreacio)) {
      res.status(400).json({ status: "KO", error: "Invalid date format, use YYYY-MM-DD" });
      return;
    }
    filters.createdAt = date;
  }
  
  filters.usuariId = req.params.usuariId;
  usuariService.getTasquesFromUsuari(filters).then((tasques:any) => {
    res.status(200).json({ status: "OK", tasques });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });
};


const createNewUser = async (req: Request, res: Response) => {
  const { body } = req;
  if (
    !body.username ||
    !body.fullName
  ) {
    res.status(400).json({ status: "KO", error: "One of the following keys is missing or is empty in request body: 'username', 'fullName'" });
    return;
  }

  const newUsuari = {
    id: uuid(),
    username: body.username,
    fullName: body.fullName,
  };

  usuariService.createNewUsuari(newUsuari).then((createdUsuari:any) => {
    res.status(201).json({ status: "OK", createdUsuari });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const deleteOneUsuari = (req: Request, res: Response) => {
  const {
    params: { usuariId },
  } = req;
  if (!usuariId) {
    res.status(400).json({ status: "KO", error: "Missing usuariId" });
    return;
  }

  usuariService.deleteOneUsuari(usuariId).then(() => {
    res.status(200).json({ status: "OK", message: "Usuari deleted" });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

export default {
  deleteOneUsuari,
  getAllUsuaris,
  createNewUser,
  getTasquesFromUsuari,
};