<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title text-center mb-5">Страхование автомобилей</h1>
            
            <div class="card border-0 shadow mb-4">
                <div class="card-body p-5">
                    <h3 class="mb-4">Виды страхования</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-shield-alt fa-3x text-gold mb-3"></i>
                                    <h5>КАСКО</h5>
                                    <p class="text-muted">Полная защита автомобиля от угона и ущерба</p>
                                    <p class="text-gold fw-bold">от 45 000 ₽/год</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-file-invoice fa-3x text-gold mb-3"></i>
                                    <h5>ОСАГО</h5>
                                    <p class="text-muted">Обязательное страхование автогражданской ответственности</p>
                                    <p class="text-gold fw-bold">от 5 800 ₽/год</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-heartbeat fa-3x text-gold mb-3"></i>
                                    <h5>ДМС для водителя</h5>
                                    <p class="text-muted">Медицинская страховка для водителя и пассажиров</p>
                                    <p class="text-gold fw-bold">от 3 500 ₽/год</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-suitcase fa-3x text-gold mb-3"></i>
                                    <h5>Страхование путешествий</h5>
                                    <p class="text-muted">Защита при поездках за границу на автомобиле</p>
                                    <p class="text-gold fw-bold">от 1 500 ₽/поездка</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Рассчитать стоимость страховки</h4>
                    <form action="/services/calculateInsurance" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Ваше имя" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="tel" class="form-control" name="phone" placeholder="Телефон" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <select class="form-select" name="insurance_type">
                                    <option selected disabled>Тип страховки</option>
                                    <option>КАСКО</option>
                                    <option>ОСАГО</option>
                                    <option>ДМС</option>
                                    <option>Комплексное страхование</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="car_model" placeholder="Марка и модель авто">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold w-100">Получить расчет</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>