var mongoose = require('mongoose')
   ,Schema = mongoose.Schema;

var dataSchema = new Schema({
    macid: String,
    company_id: String,
    device_name: String,
    serial_no: String,
    user_id: Schema.Types.ObjectId,
    user_id_str: String,
    verified: String,
    is_verified: {type: Number, default: 0} ,
    status: {type: Number, default: 0} ,
    created_at_timestamp: {type: Number},
    created_at: Date,
    updated_at: {type: Date, default: Date.now}
});

module.exports = mongoose.model('inverters', dataSchema);