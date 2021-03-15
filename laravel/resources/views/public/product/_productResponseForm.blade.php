@if (Session::has('user'))
    <div class="row top-15 bottom-30">
        <div class="col-xs-12">
            <h4>
                Оставьте свой отзыв
            </h4>
            <div class="responseForm top-15">
                <form class="responseForm__form">
                    <div class="responseForm__rating bottom-15" >
                        <span class="labelText">
                            Оценить*:
                        </span>
                        <div class="ratingForm">
                            <input type="radio" id="star-5" name="rating" checked value="5">
                            <label for="star-5" title="Оценка «5»">
                                <i class="fa fa-star"></i>
                            </label>
                            <input type="radio" id="star-4" name="rating" value="4">
                            <label for="star-4" title="Оценка «4»">
                                <i class="fa fa-star"></i>
                            </label>
                            <input type="radio" id="star-3" name="rating" value="3">
                            <label for="star-3" title="Оценка «3»">
                                <i class="fa fa-star"></i>
                            </label>
                            <input type="radio" id="star-2" name="rating" value="2">
                            <label for="star-2" title="Оценка «2»">
                                <i class="fa fa-star"></i>
                            </label>
                            <input type="radio" id="star-1" name="rating" value="1">
                            <label for="star-1" title="Оценка «1»">
                                <i class="fa fa-star"></i>
                            </label>
                        </div>
                    </div>
                    <div class="responseForm__fio bottom-15">
                        <label>
                                Ваше имя*:
                            <input 
                                type="text" 
                                class="form-control" 
                                name="fio" 
                                value="{{Session::get('user')['name']}}" 
                                placeholder="Введите имя">
                        </label>
                    </div>
                    <div class="responseForm__response bottom-15">
                        <label>
                            <span >
                                Ваше отзыв*:
                            </span>
                            <textarea 
                                name="response" 
                                class="form-control" 
                                placeholder="Введите отзыв"></textarea>
                        </label>
                    </div>
                    <div class="responseForm__buttons">
                        <input type="submit" value="Отправить" class="btn btn-primary">
                    </div>
                    <div class="responseForm__errors" style="display:none">
                        Пожалуйста, введите отзыв
                    </div>
                    <div class="checkbox col-xs-12">
                        <label>
                            <input type="checkbox" id="pers_data" checked name="pers_data">
                            Согласен на <a class="uni-link" target="_blank" href="/confirm">обработку
                                персональных данных</a>
                        </label>
                    </div>
                    <input type="text" class="hidden" name="tovar_id" value="{{$product->id}}">
                    <input type="text" class="hidden" name="user_id" value="{{Session::get('user')['id']}}">
                </form>
                <div class="responseForm__succsess" style="display:none"></div>
            </div>
        </div>
    </div>
@endif