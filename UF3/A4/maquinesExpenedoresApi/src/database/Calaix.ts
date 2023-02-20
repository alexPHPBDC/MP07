import { DataTypes, Model } from 'sequelize';
import  db  from '../config/database.config';
import { MaquinaDb } from './Maquina';

interface CalaixAttributes {
    id: string;
    maquina: string;
    casella: string;
}

export class CalaixDb extends Model<CalaixAttributes> { }

CalaixDb.init(
    {
    id: {
        type: DataTypes.UUID,
        primaryKey: true,
        allowNull: false,
    },
    maquina: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {model:MaquinaDb,key:'id'},
    },
    casella: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    
},
{
    name: {
        singular: 'calaixos',
        plural: 'calaixos',
      },
    timestamps: true,
    sequelize:db,
    tableName:'calaixos',
    freezeTableName: true
}

);


