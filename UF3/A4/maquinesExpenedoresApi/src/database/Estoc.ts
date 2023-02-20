import { DataTypes, Model } from 'sequelize';
import  db  from '../config/database.config';
import  {CalaixDb}  from './Calaix';
import { ProducteDb } from './Producte';
interface EstocAttributes {
    id: string;
    producte: string;
    caducitat: Date;
    dataVenda: Date | null;
    ubicacio: string;
}

export class EstocDb extends Model<EstocAttributes> { }

EstocDb.init(
    {
    id: {
        type: DataTypes.UUID,
        primaryKey: true,
        allowNull: false,
    },
    producte: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {model:ProducteDb,key:'id'},

    },
    caducitat: {
        type: DataTypes.DATEONLY,
        allowNull: false,
    },
    dataVenda: {
        type: DataTypes.DATEONLY,
        allowNull: true,
        defaultValue: null,
    },
    ubicacio: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {model:CalaixDb,key:'id'},
    },
},
{
    name: {
        singular: 'estocs',
        plural: 'estocs',
      },
    timestamps: true,
    sequelize:db,
    tableName:'estocs',
    freezeTableName: true
}

);


