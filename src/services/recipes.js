const client = require('../util/openeats')
const db = require('../util/db')

const softDeleteFilter = x => !x.deleted

const create = async (recipe) => {
  const ref = { id: recipe.id, name: recipe.name }
  db.get('recipes').push(ref).write()

  const oeInstance = (await client.createRecipe({
    title: recipe.name,
    info: recipe.description,
    ingredient_groups: [
      {
        ingredients: recipe.ingredients.map((x) => ({
          title: x.name
        }))
      }
    ],
    directions: recipe.directions.map((x, i) => ({
      step: i + 1,
      title: x
    })),
    prep_time: recipe.mashTime,
    cook_time: recipe.boilTime,
    servings: recipe.batchSize,
    public: false,
    course: { id: 1 },
    cuisine: { id: 1 }
  })).body

  ref.openEatsId = oeInstance.slug

  db.get('recipes').find({ id: ref.id }).assign(ref).write()

  return recipe
}

const list = () => {
  db.read()
  return db.get('recipes').filter(softDeleteFilter).value().map(({ id, name }) => ({ id, name }))
}

const get = async (id) => {
  db.read()
  const recipe = db.get('recipes').filter(softDeleteFilter).find({ id }).value()

  const oeResult = await client.getRecipe(recipe.openEatsId)

  return {
    id: recipe.id,
    name: oeResult.title,
    description: oeResult.info,
    ingredients: oeResult.ingredient_groups.pop().ingredients.map((i) => ({ name: i.title })),
    directions: JSON.parse(oeResult.directions.replace(/'/g, '"')).map((s) => s.title),
    mashTime: oeResult.prep_time,
    boilTime: oeResult.cook_time,
    boilSize: oeResult.servings
  }
}

const remove = (id) => {
  db.read()

  const ref = db.get('recipes').filter(softDeleteFilter).find({ id }).value()

  if (!ref) {
    throw new Error(`Recipe with id ${id} not found!`)
  }

  ref.deleted = true
  db.find({ id }).assign(ref).write()
}

module.exports = { create, list, get, remove }
