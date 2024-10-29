var mongoose = require('mongoose')
   ,Schema = mongoose.Schema;

var daily_activitySchema = new Schema({
    macid: String,
    status: String,
    company_id: String,
    is_device_status_update: Number,
    created_at_timestamp: {type: Number},
    created_at: Date,
    updated_at: {type: Date, default: Date.now}
});

module.exports = mongoose.model('daily_activity', daily_activitySchema,'daily_activity');