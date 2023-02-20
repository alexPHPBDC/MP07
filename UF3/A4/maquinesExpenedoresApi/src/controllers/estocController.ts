import { Request, Response } from "express";
import { Op } from "sequelize";
import estocService from "../services/estocService";

const getAllEstocs = async (req: Request, res: Response) => {
  let filters: any = {};
  const venda: any = req.query.venda;
  const disponible: any = req.query.disponible;

  if (venda) {
    const date = new Date(venda);
    if(isNaN(date.getTime()) || !/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(venda)) {
      res.status(400).json({ status: "KO", error: "Invalid date format, use YYYY-MM-DD" });
      return;
    }
    filters = { dataVenda: date };
  }
  if (disponible!==undefined) {
    filters = { 
      dataVenda:{
        [Op.is]: null
      }
     };
  }

  estocService.getAllEstocs(filters).then((allEstocs) => {
    res.status(200).json({ status: "OK", allEstocs });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const getOneEstoc = async (req: Request, res: Response) => {
 
 estocService.getOneEstoc(req.params.estocId).then((estoc) => {
    res.status(200).json({ status: "OK", estoc });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const getEstocsForProducte = async (req: Request, res: Response) => {
  let filters: any = {};

  const disponible: any = typeof(req.query.disponible) ==='string' ? true: undefined;
  if (disponible) {
    filters = { 
      dataVenda:{
        [Op.is]: null
      }
     };
  }

  filters.producte = req.params.producteId;
  
  estocService.getEstocsForProducte(filters).then((estoc) => {
    res.status(200).json({ status: "OK", estoc });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

}



const createNewEstoc = async (req: Request, res: Response) => {
  const { body } = req;
  if (
    !body.producte ||
    !body.caducitat ||
    !body.ubicacio
  ) {
    res.status(400).json({ status: "KO", error: "One of the following keys is missing or is empty in request body: 'producte', 'caducitat', 'ubicacio'" });
    return;
  }

  const newEstoc = {
    producte: body.producte,
    caducitat: body.caducitat,
    ubicacio: body.ubicacio,
  };

  const date = new Date(newEstoc.caducitat);
  if(isNaN(date.getTime()) || !/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(newEstoc.caducitat)) {
    res.status(400).json({ status: "KO", error: "Invalid date format, use YYYY-MM-DD" });
    return;
  }

  estocService.createNewEstoc(newEstoc).then((createdEstoc) => {
    res.status(201).json({ status: "OK", createdEstoc });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const updateOneEstoc = async (req: Request, res: Response) => {

  const {
    body,
    params: { estocId },
  } = req;

  if (!estocId) {
    res.status(400).json({ status: "KO", error: "Missing estocId" });
    return;
  }

  if(body.caducitat && !/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(body.caducitat)) {
    res.status(400).json({ status: "KO", error: "Invalid date format for caducitat, use YYYY-MM-DD" });
    return;
  }
  if(body.dataVenda && !/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/.test(body.dataVenda)) {
    res.status(400).json({ status: "KO", error: "Invalid date format for dataVenda, use YYYY-MM-DD" });
    return;
  }

  estocService.updateOneEstoc(estocId, body).then(() => {
    res.status(201).json({ status: "OK",  message: "Estoc updated" });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const deleteOneEstoc = (req: Request, res: Response) => {
  const {
    params: { estocId },
  } = req;
  if (!estocId) {
    res.status(400).json({ status: "KO", error: "Missing estocId" });
    return;
  }

  estocService.deleteOneEstoc(estocId).then(() => {
    res.status(200).json({ status: "OK", message: "Estoc deleted" });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

export default {
  getAllEstocs,
  getOneEstoc,
  createNewEstoc,
  updateOneEstoc,
  deleteOneEstoc,
  getEstocsForProducte,
};