import { Request, Response } from "express";
import { v4 as uuid } from "uuid";
import tascaService from "../services/tascaService";

const getOneTasca = async (req: Request, res: Response) => {
  
  tascaService.getOneTasca(req.params.tascaId).then((tasca:any) => {
    res.status(201).json({ status: "OK", tasca });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const updateOneTasca = async (req: Request, res: Response) => {

  const {
    body,
    params: { tascaId },
  } = req;

  if (!tascaId) {
    res.status(400).json({ status: "KO", error: "Missing tascaId" });
    return;
  }


  tascaService.updateOneTasca(tascaId, body).then(() => {
    res.status(200).json({ status: "OK", message: "Tasca updated" })
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const createNewTasca = async (req: Request, res: Response) => {
  const { body } = req;
  if (
    !body.user ||
    !body.title ||
    !body.description ||
    !body.status
  ) {
    res.status(400).json({ status: "KO", error: "One of the following keys is missing or is empty in request body: 'user', 'title','description','status'" });
    return;
  }

  const newTasca = {
    id: uuid(),
    user: body.user,
    title: body.title,
    description: body.description,
    status: body.status,
  };

  tascaService.createNewTasca(newTasca).then((createdTasca:any) => {
    res.status(201).json({ status: "OK", createdTasca });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const deleteOneTasca = (req: Request, res: Response) => {
  const {
    params: { tascaId },
  } = req;
  if (!tascaId) {
    res.status(400).json({ status: "KO", error: "Missing tascaId" });
    return;
  }

  tascaService.deleteOneTasca(tascaId).then(() => {
    res.status(200).json({ status: "OK", message: "Tasca deleted" });
  }).catch((err:any) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

export default {
  getOneTasca,
  deleteOneTasca,
  updateOneTasca,
  createNewTasca,
};