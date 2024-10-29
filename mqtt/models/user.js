var mongoose = require('mongoose')
   ,Schema = mongoose.Schema;

var dataSchema = new Schema({
    name: String,
    email: String,
    roll_id: Number,
    status: {type: Number, default: 0} ,
    created_at: Date,
    updated_at: {type: Date, default: Date.now}
});

module.exports = mongoose.model('users', dataSchema);