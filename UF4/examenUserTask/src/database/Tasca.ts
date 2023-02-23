import { DataTypes, Model } from 'sequelize';
import  db  from '../config/database.config';
import { UsuariDb } from './Usuari';

interface TaskAttributes {
    id: string;
    user: string;
    title: string;
    description: string;
    status: string;
}

export class TascaDb extends Model<TaskAttributes> { }

TascaDb.init(
    {
    id: {
        type: DataTypes.UUID,
        primaryKey: true,
        allowNull: false,
    },
    user: {
        type: DataTypes.STRING,
        allowNull: false,
        references: {model:UsuariDb,key:'id'},
    },
    title: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    description: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    status: {
        type: DataTypes.UUID,
        allowNull: false,
       
    }
},
{
    name: {
        singular: 'tasks',
        plural: 'tasks',
      },
    timestamps: true,
    sequelize:db,
    tableName:'tasks',
    freezeTableName: true
}
);

