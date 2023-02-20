import { Request, Response } from "express";
import maquinaService from "../services/maquinaService";

const getAllMaquines = async (req: Request, res: Response) => {
  
  maquinaService.getAllMaquines().then((allMaquines) => {
    res.status(201).json({ status: "OK", allMaquines });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const getOneMaquina = async (req: Request, res: Response) => {
  
  maquinaService.getOneMaquina(req.params.maquinaId).then((maquina) => {
    res.status(201).json({ status: "OK", maquina });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

};

const getEstocsForMaquina = async (req: Request, res: Response) => {
  let filters: any = {};
  const disponible: any = typeof(req.query.disponible) ==='string' ? true: undefined;
  filters.maquina = req.params.maquinaId;

  if (disponible !== undefined) {
    filters.disponible = true;
  }

  maquinaService.getEstocsForMaquina(filters).then((calaixos) => {
    res.status(201).json({ status: "OK", calaixos });
  }).catch((err) => {
    res.status(500).json({ status: "KO", error: err });
  });

}


const getCalaixosForMaquina = async (req: Request, res: Response) => {
  let filters: any = {};
  const buits: any = typeof(req.query.buits) ==='string' ? true: undefined;
  filters.maquina = req.params.maquinaId;

  if (buits !== undefined) {
    filters.buits = true;
  }
  
    maquinaService.getCalaixosForMaquina(filters).then((calaixos) => {
      res.status(201).json({ status: "OK", calaixos });
    }).catch((err) => {
      res.status(500).json({ status: "KO", error: err });
    });


}

export default {
  getAllMaquines,
  getOneMaquina,
  getEstocsForMaquina,
  getCalaixosForMaquina
};