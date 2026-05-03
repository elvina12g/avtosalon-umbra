<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title text-center mb-5">Лизинг автомобилей</h1>
            
            <div class="card border-0 shadow mb-4">
                <div class="card-body p-5">
                    <h3 class="mb-4">Преимущества лизинга в Umbra</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-gold"></i>
                                </div>
                                <div>
                                    <h5>Низкие ставки</h5>
                                    <p class="text-muted">Ставки от 4.5% годовых для юридических лиц</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-gold"></i>
                                </div>
                                <div>
                                    <h5>Быстрое одобрение</h5>
                                    <p class="text-muted">Решение за 24 часа, минимальный пакет документов</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-gold"></i>
                                </div>
                                <div>
                                    <h5>Гибкие условия</h5>
                                    <p class="text-muted">Индивидуальный график платежей, отсрочка платежа</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-gold"></i>
                                </div>
                                <div>
                                    <h5>Налоговые преимущества</h5>
                                    <p class="text-muted">Экономия на налоге на имущество и транспортном налоге</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Оставить заявку на лизинг</h4>
                    <form action="/services/submitLeasing" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="company" placeholder="Название компании" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="contact_person" placeholder="Контактное лицо" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="tel" class="form-control" name="phone" placeholder="Телефон" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="col-12 mb-3">
                                <select class="form-select" name="car_interest">
                                    <option selected disabled>Интересуемый автомобиль</option>
                                    <option>Mercedes-Benz</option>
                                    <option>BMW</option>
                                    <option>Audi</option>
                                    <option>Porsche</option>
                                    <option>Другой</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <textarea class="form-control" name="comment" rows="3" placeholder="Комментарий"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold w-100">Отправить заявку</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>