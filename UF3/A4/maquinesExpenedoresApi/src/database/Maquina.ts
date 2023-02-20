import { DataTypes, Model } from 'sequelize';
import  db  from '../config/database.config';
interface MaquinaAttributes {
    id: string;
    municipi: string;
    adreca: Date;
}

export class MaquinaDb extends Model<MaquinaAttributes> { }

MaquinaDb.init(
    {
    id: {
        type: DataTypes.UUID,
        primaryKey: true,
        allowNull: false,
    },
    municipi: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    adreca: {
        type: DataTypes.STRING,
        allowNull: false,
    },

},
{
    name: {
        singular: 'maquines',
        plural: 'maquines',
      },
    timestamps: true,
    sequelize:db,
    tableName:'maquines',
    freezeTableName: true
}

);


