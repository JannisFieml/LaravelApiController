# A small package to generate basic migrations, models, requests, controllers and routes by editing yaml files

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jannisfieml/laravelapigenerator.svg?style=flat-square)](https://packagist.org/packages/jannisfieml/laravelapigenerator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/jannisfieml/laravelapigenerator/run-tests?label=tests)](https://github.com/jannisfieml/laravelapigenerator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/jannisfieml/laravelapigenerator/Check%20&%20fix%20styling?label=code%20style)](https://github.com/jannisfieml/laravelapigenerator/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jannisfieml/laravelapigenerator.svg?style=flat-square)](https://packagist.org/packages/jannisfieml/laravelapigenerator)

---

## Installation

You can install the package via composer:

```bash
composer require jannisfieml/laravelapigenerator
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="JannisFieml\LaravelApiGenerator\LaravelApiGeneratorServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
<?php
// config for Jannisfieml/LaravelApiGenerator

return [
    // Class that every generated Controller extends
    'controller_base_class' => 'App\Http\Controllers\Controller',

    // Class that every generated Model extends
    'model_base_class' => 'Illuminate\Database\Eloquent\Model',
];

```

## Usage

### General

This package generates basic functionality for your laravel api. You could use them as they are,
but in some cases you need to change some things yourself.
The generated files should still give you a great starting point and reduce development time in the beginning of a project.

After you changed your schemas and rerun the commands the generated files will be updated,
so keep in mind that your manual changes will be overwritten.
Be sure your schemas are as you want them to be and only then change the code or just don't rerun the commands.

Migrations, Models, Requests and Controllers are each split in separate commands,
so you can also choose to just update the file-type you didn't modify (i.e. Models).

### Start with creating your schemas

This command generates a .yaml file that you can use to write your model definition:
```bash
php artisan generate:schema MyModel
```

The generated 0_my_model.yaml looks like this:
```yaml
name: "MyModel"
attributes:
    -
        name: "attribute"
        type: "string"
        props: []
        validations: []

```

Modify the file as you wish. The name does not need to have the same title as the schema file itself, but it helps keep things organised.

The number prefix is important, as it is used for ordering the migrations.
Some table needs to be created before others, so the order the files as the migrations should be executed.

- name: name of the model
- attributes: array of model attributes. Numeric ID and timestamps are generated automatically.
    - name: name of attribute
    - type: type that would be used in a migration (i.e. "integer", "string", "foreignId", ...)
    - props: array of props used in migration (i.e. "nullable", "constrained", ...)
    - validations: array of default laravel validations (i.e. "required", "existing:users,id", ...)
    
This command always generates a new file and does not modify existing ones.
Every other command will loop through the schemas directory and will generate files according to your definitions.

For the following examples we will use this schema:
```yaml
name: "MyModel"
attributes:
    -
        name: "name"
        type: "string"
        props: []
        validations: ["required", "unique:my_model,id"]
    -
        name: "description"
        type: "text"
        props: ["nullable"]
        validations: []
    -
        name: "amount"
        type: "integer"
        props: ["nullable"]
        validations: []
    -
        name: "related_model_id"
        type: "foreignId"
        props: ["constrained"]
        validations: ["required"]

```

### Generate your migrations

```bash
php artisan generate:migrations
```

The generated migrations will look like this:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyModelsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('my_models', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string('name');
			$table->text('description')->nullable();
			$table->integer('amount')->nullable();
			$table->foreignId('related_model_id')->constrained();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('my_models');
	}
}

```
Run your migrations as usual.
```bash
php artisan migrate
```

### Generate your models

```bash
php artisan generate:models
```

As you see, the model will put any defined attributes in the fillable property of the model.
Getters and Setters of attributes and BelongsTo methods of foreign-keys are also generated.

The generated models will look like this:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyModel extends Model
{
	use HasFactory;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'my_models';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'description', 'amount', 'related_model_id'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [];


	public function getId(): int
	{
		return $this->getAttribute('id');
	}


	public function getName(): string
	{
		return $this->getAttribute('name');
	}


	public function setName(string $name): self
	{
		$this->setAttribute('name', $name);

		return $this;
	}


	public function getDescription(): string
	{
		return $this->getAttribute('description');
	}


	public function setDescription(string $description): self
	{
		$this->setAttribute('description', $description);

		return $this;
	}


	public function getAmount(): int
	{
		return $this->getAttribute('amount');
	}


	public function setAmount(int $amount): self
	{
		$this->setAttribute('amount', $amount);

		return $this;
	}


	public function getRelatedModelId(): int
	{
		return $this->getAttribute('related_model_id');
	}


	public function setRelatedModelId(int $related_model_id): self
	{
		$this->setAttribute('related_model_id', $related_model_id);

		return $this;
	}


	public function relatedModel(): BelongsTo
	{
		return $this->belongsTo(RelatedModel::class);
	}
}

```

### Generate requests

```bash
php artisan generate:requests
```

The requests are used in generated Controllers for create and update actions.
One specific request is created for those two actions respectively.

The generated requests will look like this:
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMyModelRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}


	public function rules(): array
	{
		return [
			'name' => 'required|unique:my_model,id|string',
			'description' => 'string',
			'amount' => 'int',
			'related_model_id' => 'required|int',
		];
	}
}

```

### Generate controllers

```bash
php artisan generate:controllers
```

The generated controllers are specifically for api crud actions, so specific ApiResponses are returned and no views.
You are free to change this yourself, but as written before the command will overwrite your changes if run again.

The generated controllers will look like this:
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMyModelRequest;
use App\Http\Requests\UpdateMyModelRequest;
use App\Models\MyModel;
use Jannisfieml\LaravelApiGenerator\Responses\ApiDataResponse;
use Jannisfieml\LaravelApiGenerator\Responses\ApiErrorResponse;
use Jannisfieml\LaravelApiGenerator\Responses\ApiResponse;
use Jannisfieml\LaravelApiGenerator\Services\ArrayTransformerService;

class MyModelController extends Controller
{
	public function getMyModels(): ApiResponse
	{
		$data = MyModel::all();

		return new ApiDataResponse($data->toArray());
	}


	public function createMyModel(CreateMyModelRequest $request): ApiResponse
	{
		$arrayTransformerService = new ArrayTransformerService();
		$myModel = new MyModel(
			$arrayTransformerService->transformToSnakeCase($request->toArray())
		);
		$myModel->save();

		return new ApiDataResponse($myModel->toArray());
	}


	public function updateMyModel(UpdateMyModelRequest $request, int $id): ApiResponse
	{
		$arrayTransformerService = new ArrayTransformerService();
		$myModel = MyModel::find($id);
		$myModel->update(
			$arrayTransformerService->transformToSnakeCase($request->toArray())
		);
		$myModel->save();

		return new ApiDataResponse($myModel->toArray());
	}


	public function deleteMyModel(int $id): ApiResponse
	{
		$myModel = MyModel::find($id);
		$myModel->delete();

		return new ApiDataResponse();
	}
}

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jannis Fieml](https://github.com/JannisFieml)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
