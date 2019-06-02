const queue = require('./util/queue')

queue.process('create.recipe', (job) => console.log(job.data))
