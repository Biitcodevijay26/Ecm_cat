var mongoose = require('mongoose')
   ,Schema = mongoose.Schema;

var dataSchema = new Schema({
    topic: String,
    macid: String,
    company_id: String,
    data: Object,
    status: {type: Number, default: 0} ,
    created_at_timestamp: {type: Number},
    created_at: Date,
    updated_at: {type: Date, default: Date.now}
});

module.exports = mongoose.model('datas', dataSchema);