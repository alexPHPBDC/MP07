import { Request, Response } from "express";
import producteService from "../services/producteService";
import { v4 as uuid } from "uuid";
const getAllProductes = async (req: Request, res: Response) => {

  producteService.getAllProductes().then((allProductes) => {
    res.status(201).json({ status: "OK", allProductes });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const getOneProducte = async (req: Request, res: Response) => {

  producteService.getOneProducte(req.params.producteId).then((producte) => {
    res.status(201).json({ status: "OK", producte });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const createNewProducte = async (req: Request, res: Response) => {
  const { body } = req;
  if (!body.nom || !body.tipus || !body.preu || !body.categoria) {
    res.status(400).json({ status: "KO", error: "One of the following keys is missing or is empty in request body: 'nom', 'tipus', 'preu', 'categoria'" });
    return;
  }

  const newProducte = {
    id: uuid(),
    nom: body.nom,
    tipus: body.tipus,
    preu: body.preu,
    categoria: body.categoria,
  };

  producteService.createNewProducte(newProducte).then((createdProducte) => {
    res.status(200).json({ status: "OK", createdProducte })
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });


};

const updateOneProducte = async (req: Request, res: Response) => {

  const {
    body,
    params: { producteId },
  } = req;

  if (!producteId) {
    res.status(400).json({ status: "KO", error: "Missing producteId" });
    return;
  }


  producteService.updateOneProducte(producteId, body).then(() => {
    res.status(200).json({ status: "OK", message: "Producte updated" })
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const deleteOneProducte = (req: Request, res: Response) => {
  const {
    params: { producteId },
  } = req;
  if (!producteId) {
    res.status(400).json({ status: "KO", error: "Missing producteId" });
    return;
  }

  producteService.deleteOneProducte(producteId).then(() => {
    res.status(200).json({ status: "OK", message: "Producte deleted" })
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

export default {
  getAllProductes,
  getOneProducte,
  createNewProducte,
  updateOneProducte,
  deleteOneProducte,
};