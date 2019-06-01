const express = require('express')
const router = express.Router()

router.get('/v1/recipe', require('./search'))
router.post('/v1/recipe', require('./create'))

module.exports = router
