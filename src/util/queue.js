const kue = require('kue')

const queue = kue.createQueue({
  redis: {
    host: 'redis'
  }
})

module.exports = queue
