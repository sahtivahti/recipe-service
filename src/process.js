const kue = require('kue')

const queue = kue.createQueue({
  redis: {
    host: 'redis'
  }
})

queue.process('create.recipe', (job) => console.log(job.data))
