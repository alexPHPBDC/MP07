import { DataTypes, Model } from 'sequelize';
import  db  from '../config/database.config';
import { CategoriaDb } from './Categoria';

interface ProducteAttributes {
    id: string;
    nom: string;
    tipus: string;
    preu: string;
    categoria: string;
}

export class ProducteDb extends Model<ProducteAttributes> { }

ProducteDb.init(
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
    tipus: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    preu: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    categoria: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {model:CategoriaDb,key:'id'},
    }
},
{
    name: {
        singular: 'productes',
        plural: 'productes',
      },
    timestamps: true,
    sequelize:db,
    tableName:'productes',
    freezeTableName: true
}
);

