var mongoose = require('mongoose')
   ,Schema = mongoose.Schema;

var deviceWarningSchema = new Schema({
    macid: String,
    code: String,
    company_id: String,
    code_date: Date,
    status: {type: Number, default: 1} ,
    created_at_timestamp: {type: Number},
    created_at: Date,
    updated_at: {type: Date, default: Date.now}
});

module.exports = mongoose.model('device_warning', deviceWarningSchema);