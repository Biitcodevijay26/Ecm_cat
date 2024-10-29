var mongoose = require('mongoose')
   ,Schema = mongoose.Schema;

var dataSchema = new Schema({
    inverter_id: String,
    inverter_start_stop: Number,
    meter: Object,
    grid_vac: Object,
    grid_fac: Object,
    grid_10min_high: Object,
    battery_min_capcity: Object,
    battery_charge_max_current: Object,
    battery_discharge_max_current: Object,
    grid_tie_limit_en_di: Number,
    discharge_min_capcity: Object,
    dischCutOffCapacity_GridMode: Object,
    operating_mode: Number,
    charge_period_1: Object,
    CP1_max_cap: Number,
    charge_period_2: Object,
    CP2_max_cap: Number,
    BackUp_GridChargeEN: Number,
    backup_mode: Object,
    EPS_Mute: Number,
    EPS_AutoRestart: Number,
    EPS_Frequency: Number,
    EPS_MinEscSoc: Number,
    Export_control_User_Limit: Number,
    info_menu_date: Object,
    info_menu_time: Object,
    created_at_timestamp: {type: Number},
    created_at: Date,
    updated_at: {type: Date, default: Date.now}
});

module.exports = mongoose.model('inverter_settings', dataSchema);