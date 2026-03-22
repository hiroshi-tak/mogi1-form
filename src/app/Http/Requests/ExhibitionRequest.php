<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required','max:255'],
            'image' => ['required','mimes:jpeg,png'],
            'categories' => ['required','array'],
            'categories.*' => ['exists:categories,id'],
            'condition' => ['required'],
            'price' => ['required','integer','min:0'],
            'brand' => ['nullable','string','max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明を255文字以下で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'categories.required' => '商品のカテゴリーを選択してください',
            'categories.array' => '商品のカテゴリーの形式が不正です',
            'categories.*.exists' => '存在しない商品のカテゴリーが選択されています',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格を数値で入力してください',
            'price.min' => '販売価格を0円以上で入力してください',
        ];
    }
}
