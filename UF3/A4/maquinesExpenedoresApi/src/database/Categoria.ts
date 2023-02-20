import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
interface CategoriaAttributes {
    id: string
    nom: string
    iva: string
}

export class CategoriaDb extends Model<CategoriaAttributes> { }

CategoriaDb.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false,
        },
        nom: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        iva: {
            type: DataTypes.STRING,
            allowNull: false,
        },
    },
    {
        name: {
            singular: 'categories',
            plural: 'categories',
          },
        timestamps: true,
        sequelize: db,
        tableName: 'categories',
        freezeTableName: true
    }

);

